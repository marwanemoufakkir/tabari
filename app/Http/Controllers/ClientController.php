<?php

namespace App\Http\Controllers;

use App\Models\Ayah;
use App\Models\Topic;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use ONGR\ElasticsearchDSL\Highlight\Highlight;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchPhraseQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\Sort\FieldSort;



class ClientController extends Controller
{

    protected $elasticsearch;
    public $per_page = 10;
    //Set up our client
    public function __construct()
    {
        $this->elasticsearch = ClientBuilder::create()->build();

    }
    public function elasticsearchQueries(Request $request)
    {
        $query = '';
        if ($request->kt_docs_repeater_advanced[0]['content'] != '') {
            $query = $request->kt_docs_repeater_advanced[0]['content'];
        }

        $from = ($request->get('page', 1) - 1) * $this->per_page;
        $items = $this->searchOnElasticsearchBool($request, $from, $this->per_page);
        // dd($items);
        $pagination = new LengthAwarePaginator(
            $items['hits']['hits'],
            $items['hits']['total']['value'],
            $this->per_page,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('results')->with('result', $this->buildCollection($items))->with('count', $this->getTotal($items))->with('query', $query)->with('pagination', $pagination);
    }
    public function fetchAyah(Request $request)
    {
        $items = $this->findAyahXML($request->id);
        // dd($items);
        return view('ayah')->with('result', $items['_source']);
    }
    public function fetchSurahAyah(Request $request)
    {
        $ayah = Ayah::get()->where('surah_id', $request->surah_id);
        return response()->json(['data' => $ayah], 201);
    }
    public function fetchSubTopics(Request $request)
    {
        $subtopics = Topic::where('tag', $request->topic)->with('subtopics')->get();
       
        return response()->json(['data' =>$subtopics], 201);
    }
    private function findAyahXML(string $query = ''): array
    {
        $params = [
            'index' => 'my-tafsir',
            'id' => $query,
        ];

        $items = $this->elasticsearch->get($params);

        return $items;
    }

    private function searchOnElasticsearchBool(Request $request, $from = 1, $per_page = 10)
    {

        $requestParams = $request->kt_docs_repeater_advanced;
        // dd($requestParams);
        $search = new Search();
        $boolQuery = new BoolQuery();

        foreach ($requestParams as $key => $param) {

            $param = array_filter($param);
            $searchType = $param['search_type'];
            if (isset($param['bool'])) {
                $boolOperator = $param['bool'];
            } else {
                $boolOperator = 'MUST';

            }
            switch ($boolOperator) {
                case 'MUST':
                    $BoolQueryOperator = BoolQuery::MUST;
                    break;
                case 'SHOULD':
                    $BoolQueryOperator = BoolQuery::SHOULD;
                    break;
                default:
                    $BoolQueryOperator = BoolQuery::MUST_NOT;
                    break;
            }

            if(array_key_exists('subtopic', $param) && $param['subtopic']!=''){
                unset($param["topic"]);
            }
  
            foreach ($param as $key => $field) {
                if ($key === 'content') {
                    switch ($searchType) {
                        case 'default':
                            $boolQuery->add(new MatchPhraseQuery('content.rebuilt_arabic', $field), $BoolQueryOperator);
                            // $boolQuery->addParameter("operator", 'and');
                            break;
                        case 'exact':
                            $boolQuery->add(new MatchPhraseQuery('content.exact_arabic', $field), $BoolQueryOperator);

                            break;
                        case 'synonym':
                            $newTER = new MatchQuery('content', $field);
                            $newTER->addParameter('analyzer', 'arabic_synonym_normalized');
                            $boolQuery->add($newTER, $BoolQueryOperator);
         
                            break;
                        default:
                            $boolQuery->add(new MatchQuery('content.boolean_sim_field', $field), $BoolQueryOperator);

                            break;
                    }

                }

                if ($key === 'ayah' || $key === 'chapter' || $key === 'topic' || $key === 'subtopic' || $key === 'type') {
                    
                    $boolQuery->add(new TermQuery($key, $field), $BoolQueryOperator);

                }
            }

        }
        $search->addQuery($boolQuery);

        $requestFilter = $request->filter;

        if (isset($requestFilter)) {
            foreach ($requestFilter as $key => $value) {

                if ($key === 'surah') {
                    foreach ($value as $key => $term) {
                        $filter = new TermQuery('chapter', $term);
                        $search->addQuery($filter, BoolQuery::FILTER);

                    }

                }
                if ($key === 'type') {
                    foreach ($value as $key => $term) {
                        $filter = new TermQuery('type', $term);
                        $search->addQuery($filter, BoolQuery::FILTER);

                    }

                }
                if ($key === 'topic') {
                    foreach ($value as $key => $term) {
                        $filter = new TermQuery('topic', $term);
                        $search->addQuery($filter, BoolQuery::FILTER);

                    }

                }
                if ($key === 'subtopic') {
                    foreach ($value as $key => $term) {
                        $filter = new TermQuery('subtopic', $term);
                        $search->addQuery($filter, BoolQuery::FILTER);

                    }

                }
            }
        }

        $higlight = new Highlight();
        $higlight->addField('content');
        $higlight->addField('content.exact_arabic');
        $higlight->addField('content.arabic_synonym_normalized');
        $higlight->addField('content.rebuilt_arabic');
        $higlight->addField('content.boolean_sim_field');

        $higlight->setTags(["<a class='ls-2 fw-bolder' style='text-decoration: underline;'>"], ["</a>"]);
        $higlight->setFragmentSize(0);
        $higlight->setNumberOfFragments(2);
        $search->addHighlight($higlight);
        $sortField1 = new FieldSort('timestamp', FieldSort::ASC);
        $search->addSort($sortField1);
        $searchParams = [
            'index' => 'my-tafsir2',
            'from' => $from,
            'size' => $per_page,
            'body' => $search->toArray(),
        ];
        // $searchParams['sort'] = array('timestamp:asc');
        // dd(json_encode($searchParams));
        $items = $this->elasticsearch->search($searchParams);

        return $items;

    }
    private function cleanupQuery(string $query_string, $BoolQueryOperator)
    {
        $query_string = str_replace($BoolQueryOperator . '(', $BoolQueryOperator . ' (', $query_string);
        $query_string = str_replace('(AND ', '(', $query_string);
        $query_string = str_replace('( AND ', '(', $query_string);
        $words = explode(" ", $query_string);
        array_splice($words, -1);
        $query_string = implode(" ", $words);
        $query_string = str_replace('()', '(*)', $query_string);
        return $query_string;
    }
    private function buildCollection(array $items)
    {
        return $items['hits']['hits'];
    }
    private function getTotal(array $items)
    {
        $count = $items['hits']['total'];
        return $count;
    }

    public function parsingDocument(){
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
                foreach ($elements as $element) {
                    $ayahs = $xpath->evaluate("./tei:head/tei:quote", $element);
                    $topics = $xpath->evaluate("./tei:p | ./tei:div[@type='subsection']/tei:p", $element);
                    if($ayahs->length){
                        
                        foreach ($ayahs as  $id=> $value) {
                            $number = $value->getattribute("n");
                            $title = trim($value->nodeValue);
                        }

                    }else{
                        if(explode('_section',explode('sure_',$filename)[1])[0]==='0'){
                            $number ="0.0";
                            $title ="فَاتِحَةِالْكِتَابِ";
                        }else{
                            $number ="";
                            $title =""; 
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
                            if(!empty($number)){
                                $lstayah=$number;
                                }else{
                                $number=$lstayah;
                                
                            }
                            // dd(array_unique($subtopicList));
                            $array = array_unique($subtopicList, SORT_REGULAR);
                            if(!empty($content )){
                                echo "*********************************</br>";
                                 $json = preg_replace('/(\s+)?\\\t(\s+)?/', ' ', json_encode(array("chapter"=>explode('_section',explode('sure_',$filename)[1])[0],"ayah" => $number, "ayahTitle" => trim($title),"content"=>trim($topic->nodeValue),"xml_content"=>$htmlString,"type"=> $type,'topic' => $list,'subtopic' => implode(' ',$array),'narrator'=> $pers,'vol'=>$volPage,'timestamp' => strtotime("-1d")), JSON_UNESCAPED_UNICODE));
                                 $json = preg_replace('/(\s+)?\\\n(\s+)?/', ' ',$json);
                                 print_r(json_decode($json));
                                 echo "*********************************</br>";

                                //  $result=$this->elasticsearch->index([
                                //     'index' => 'my-tafsir3',
                                //     'type' => '_doc',
                                //     'body'=>json_decode($json)
                                //     ]);
                                //     print_r($result);
                                
                            }


                        }
                    }
                    
                }
                die();
            }   
        }
    }

}
