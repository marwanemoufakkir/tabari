<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tafsir;
use Elasticsearch\Client;
error_reporting(E_ALL ^ E_WARNING); 
class ParsingFiles extends Command
{
/**
 * The name and signature of the console command.
 *
 * @var string
 */
protected $signature = 'import';

/**
 * The console command description.
 *
 * @var string
 */
protected $description = 'Command description';

/**
 * Create a new command instance.
 *
 * @return void
 */


/** @var \Elasticsearch\Client */
private $elasticsearch;


public function __construct(Client $elasticsearch)
{
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
}

/**
 * Execute the console command.
 *
 * @return int
 */
public function handle()
{
    $params = ['index' => 'my-tafsir3'];
    $response = $this->elasticsearch->indices()->delete($params);
$params = [
'index' => 'my-tafsir3',
'body' => [

"settings"=>[
"similarity"=>[
"my_bm25"=>[
"type"=> "BM25",
"b"=> "0.236"
]
],
"analysis"=>[
"filter"=>[
"autocomplete"=>[
"type"=>"edge_ngram",
"min_gram"=>1,
"max_gram"=>30
],
"arabic_stop"=>[
"type"=>"stop",
"stopwords"=>"_arabic_"
],
"arabic_keywords"=>[
"type"=>"keyword_marker",
"keywords"=>[
"ياسين",
"موسي",
"عيسي",
"يوسف",
"ابراهيم",
"اسماعيل",
"نوح"
]
],
"arabic_stemmer"=>[
"type"=>"stemmer",
"language"=>"arabic"
],
"shingle_filter"=>[
"type"=> "shingle",
"min_shingle_size"=> 2,
"max_shingle_size"=> 2,
"output_unigrams"=> false
]
],
"analyzer"=>[
"autocomplete_arabic"=>[
"type"=>"custom",
"tokenizer"=>"whitespace",
"filter"=>[
"arabic_normalization",
"autocomplete",
"arabic_keywords"
]
],
"exact_arabic"=>[
"tokenizer"=>"standard",
"filter"=>[
"decimal_digit",
"arabic_normalization"
]
],
"rebuilt_arabic"=>[
"tokenizer"=>"standard",
"filter"=>[
"lowercase",
"decimal_digit",
"arabic_stop",
"arabic_normalization",
"arabic_keywords",
"arabic_stemmer"
]
],
"arabic_synonym_normalized"=>[
"tokenizer"=>"icu_tokenizer",
"filter"=>[
"arabic_keywords",
"arabic_normalization",
"arabic_stemmer",
"arabic_stop",
"icu_folding"
]
]
]
]
],
"mappings"=>[
"properties"=>[
"chapter"=>[
"type"=>"keyword"
],
"ayah"=>[
"type"=>"keyword"
],
"ayahTitle"=>[
"type"=>"text",
"analyzer"=>"autocomplete_arabic"
],
"content"=>[
"type"=>"text",
"fields"=>[
"rebuilt_arabic"=>[
"type"=>"text",
"analyzer"=>"rebuilt_arabic"
],
"exact_arabic"=>[
"type"=>"text",
"analyzer"=>"exact_arabic"
],
"arabic_synonym_normalized"=>[
"type"=>"text",
"analyzer"=>"arabic_synonym_normalized"
],
"autocomplete_arabic"=>[
"type"=>"text",
"analyzer"=>"autocomplete_arabic"
],
"boolean_sim_field"=>[
"type"=> "text",
"similarity"=> "my_bm25",
"term_vector"=> 'with_positions_offsets',
]
]
],
"xml_content"=>[
"type"=>"text",
],
"type"=>[
"type"=>"text"
],
"topic"=>[
"type"=>"text"
],
"subtopic"=>[
"type"=>"text"
],
"vol"=>[
"type"=>"text"
],
"narrator"=>[
"type"=>"nested",
"properties"=>[
"type"=>[
"type"=>"keyword"
],
"name"=>[
"type"=>"text"
]
]
]
]
]
]

];


$response = $this->elasticsearch->indices()->create($params);
$this->info('Indexing all sections. This might take a while...');

foreach ($this->getText() as $key => $value) {

    $this->output->write('.');
}

    $this->info("\nDone!");
}
protected function getText(){
    $doc = new \DOMDocument;
    $files = glob(public_path()."/surah0-114-aug22/*/*.xml",GLOB_BRACE);
    sort($files, SORT_NATURAL);

    
    if (is_array($files)) {   
        foreach($files as $filename) {
            $doc->load($filename);
            libxml_use_internal_errors(true);
            $xpath = new \DOMXPath($doc);
            libxml_clear_errors();

            $xpath->registerNamespace("tei", "http://www.tei-c.org/ns/1.0");
            $query = "//tei:div[@type='section']";
            $elements = $xpath->query($query);
            $lstvolPage='1:3';
            $lstayah='';
            $bulkBody = [];
            foreach ($elements as $element) {
                $ayahs = $xpath->evaluate("./tei:head/tei:quote", $element);
                $topics = $xpath->evaluate("./tei:p | ./tei:div[@type='subsection']/tei:p", $element);
                if($ayahs->length){
                    
                    foreach ($ayahs as  $id=> $value) {
                        $number = $value->getattribute("n");
                        $title = trim($value->nodeValue);
                        if(!empty($number)){
                            $lstayah=$number;
                            }else{
                            $number=$lstayah;
                            
                        }
                    }

                }else{
                    if(explode('_section',explode('sure_',$filename)[1])[0]==='0'){
                        $number ="0.0";
                        $title ="فَاتِحَةِالْكِتَابِ";
                    }else{
                        if(!empty($number)){
                            $lstayah=$number;
                            }else{
                            $number=$lstayah;
                            
                        }
                        // $number ="";
                        // $title =""; 
                    }

                   
                }
                if($topics->length){
                    foreach ($topics  as $key => $topic) {
                        $pers=[];
                        $volPage='';
                        $type = $topic->getattribute("n");
                        $list = trim(preg_replace("/[^A-Za-z0-9.!? ]/","",str_replace( 'yes', '', $topic->getattribute("ana"))));
                        $htmlString = htmlentities($doc->saveXML($topic));
                        $content =trim($topic->nodeValue);
                        $subtopicList=array();
                        $subtopics = $xpath->query("./tei:seg | ./tei:time", $topic);
                        foreach ($subtopics as $key => $value) {
                            
                            $subtopicList[]=trim(preg_replace("/[^A-Za-z0-9.!? ]/","",str_replace( 'yes', '', $value->getattribute("ana"))));
                        }
                        $pb = $xpath->evaluate("./tei:pb[@type='turki'] | ./tei:said/tei:pb[@type='turki'] | ./tei:seg/tei:pb[@type='turki'] | ./tei:quote/tei:seg/tei:pb[@type='turki']", $topic);
                        foreach ($pb as $key => $vol) {
                            $volPage=$vol->getattribute("n");
                        }
                        if(!empty($volPage)){
                            $lstvolPage=$volPage;
                            }else{
                            $volPage=$lstvolPage;
                        }

                        $persName = $xpath->query("./tei:persName", $topic);
                        foreach ($persName as $key => $per) {
                            $persNameType = $per->getattribute("ana");
                            $narr=$per->nodeValue;
                            $pers[]=array('type'=>$persNameType,'name'=>$narr);
                        }

                        // dd(array_unique($subtopicList));
                        $array = array_unique($subtopicList, SORT_REGULAR);
                        if(!empty($content )){
                    
                            
                             $json = preg_replace('/(\s+)?\\\t(\s+)?/', ' ', json_encode(array("chapter"=>explode('_section',explode('sure_',$filename)[1])[0],"ayah" => $number, "ayahTitle" => trim($title),"content"=>$content ,"xml_content"=>$htmlString,"type"=> $type,'topic' => $list,'subtopic' => implode(' ',$array),'narrator'=> $pers,'vol'=>$volPage,'timestamp' => strtotime("-1d")), JSON_UNESCAPED_UNICODE));
                             $json = preg_replace('/(\s+)?\\\n(\s+)?/', ' ',$json);
                            // $params['body'][]=[   'index' => [
                            //     '_index' => 'my-tafsir3',
                            //     '_type' => '_doc',
                            // ]];

                            // $params['body'][]=[json_decode($json)];
                            $bulkBody[] = ['index' => ['_index' => 'my-tafsir3', '_type' => '_doc']];
                            $bulkBody[] = json_decode($json);
                    
                            //  $result=$this->elasticsearch->index([
                            //     'index' => 'my-tafsir3',
                            //     'type' => '_doc',
                            //     'body'=>json_decode($json)
                            //     ]);
                            //     print_r($result);
                            //     time_nanosleep(0, 500000000);
                            
                        }


                    }
                }
                
            }
            // echo explode('_section',explode('sure_',$filename)[1])[0]."</br>";
            // dd($params);
            // $responses = $this->elasticsearch->bulk($params);
            $responses=$this->elasticsearch->bulk(['body' => $bulkBody]);
            $this->elasticsearch->indices()->refresh();
            print_r($responses);
            // die();
        }
        return  json_decode($json);
    }
}
}