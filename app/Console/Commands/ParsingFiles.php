<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tafsir;
use Elasticsearch\Client;




// $old = ini_set('memory_limit', '-1');

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
        $params = ['index' => 'my-tafsir'];
        $response = $this->elasticsearch->indices()->delete($params);
        $params = [
            'index' => 'my-tafsir',
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
                    "quran_arabic_word_synonym"=>[
                    "type"=> "synonym",
                   "expand"=> "true",
                   "lenient"=> "true",
                   "synonyms_path"=> "analysis/quran_arabic_word_synonym.txt"
                    ],
                  "noun_synonym"=>[
                   "type"=> "synonym",
                    "expand"=>"true",
                    "synonyms_path"=> "analysis/noun_synonym.txt"
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
                        "quran_arabic_word_synonym",
                        "noun_synonym",
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


        // Create the index with mappings and settings now
        $response = $this->elasticsearch->indices()->create($params);
        $this->info('Indexing all sections. This might take a while...');
        
        foreach ($this->getText() as $key => $value) {
            // $this->elasticsearch->index([
           	//    'index' => 'my-tafsir',
            //        'type' => '_doc',
            //        'id'=>$key,
            //        'body'=> $value
            //  ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("\nDone!");

    }
    protected function getText(){
        $doc = new \DOMDocument;
        $files = glob("Tabari_komplett_komplett.xml");
        $sections = array();
        $csv=array();
        $listTopicsDB =array();
        $i=1;
        $topicList=array();
        if (is_array($files)) {   
            foreach($files as $filename) {
                $doc->load($filename);
                $xpath = new \DOMXPath($doc);
                $xpath->registerNamespace("tei", "http://www.tei-c.org/ns/1.0");

                $query = "//tei:div[@type='section']";
                
                $elements = $xpath->query($query);
                

                foreach ($elements as $element) {
                
                    $chapters = array();
                    $ayahTable = array();
                   
                    $subtopicList='';
                    $ayahs = $xpath->query("./tei:head/tei:quote", $element);
                    
                    $topics = $xpath->evaluate("./tei:p | ./tei:div[@type='subsection']/tei:p", $element);
                    
                
                    foreach ($ayahs as  $id=> $value) {
                      // if(empty($value->getattribute("n"))){
                      //   $number = $element->getattribute("n");

                      // }else{
                      //   $number = $value->getattribute("n");

                      // }
                      $number = $value->getattribute("n");
                        $title = $value->nodeValue;
                        
                        $chapters = array("chapterNumber"=>explode(".", $number)[0],"ayahNumber" => $number, "title" => trim($title));
                        $ayahTable= array($i,explode(".", $number)[0],$number);
                        
                    }
                    
                    foreach ($topics  as $key => $topic) {
                        
                        $htmlString = htmlentities($doc->saveXML($topic));
        
                        $type = $topic->getattribute("n");
                        $list = trim($topic->getattribute("ana"));
                        $listExplode = explode(' ' ,$list);
                        $pers=[];
                        $volPage='';
                        $lstvolPage='';
                        $subtopics = $xpath->query("./tei:seg", $topic);
                        foreach ($subtopics as $key => $value) {
                            $analist = $value->getattribute("ana");
                            $subtopicList.=' '.trim($value->getattribute("ana"));
                        }

                        $pb = $xpath->query("./tei:pb[@type='turki'] | ./tei:said/tei:pb[@type='turki'] | ./tei:seg/tei:pb[@type='turki'] | ./tei:quote/tei:seg/tei:pb[@type='turki']", $topic);
                        
                        foreach ($pb as $key => $vol) {
                          
                           
                            $volPage=$vol->getattribute("n");
                         
                          
                        }
                        
                        $persName = $xpath->query("./tei:persName", $topic);
                        foreach ($persName as $key => $per) {
                            $persNameType = $per->getattribute("ana");
                            $narr=$per->nodeValue;
             

                            $pers[]=array('type'=>$persNameType,'name'=>$narr);
                        }
       
                        $number=str_replace(':','.',$number);
                        $topicList[] = array("chapter"=>explode(".", $number)[0],"ayah" => $number, "ayahTitle" => trim($title),"content"=>trim($topic->nodeValue),"xml_content"=>$htmlString,"type"=> $type,'topic' => $list,'subtopic' => $subtopicList,'narrator'=> $pers,'vol'=>$volPage,'timestamp' => strtotime("-1d"));
                       
                        $json = preg_replace('/(\s+)?\\\t(\s+)?/', ' ', json_encode(array("chapter"=>explode(".", $number)[0],"ayah" => $number, "ayahTitle" => trim($title),"content"=>trim($topic->nodeValue),"xml_content"=>$htmlString,"type"=> $type,'topic' => $list,'subtopic' => $subtopicList,'narrator'=> $pers,'vol'=>$volPage,'timestamp' => strtotime("-1d")), JSON_UNESCAPED_UNICODE));
                        $json = preg_replace('/(\s+)?\\\n(\s+)?/', ' ',$json);
                        $result=$this->elasticsearch->index([
                          'index' => 'my-tafsir',
                             'type' => '_doc',
                             'body'=>json_decode($json)
                       ]);
                       print_r($result);
                    }
                    
                
                    $section = array();
                    // $section["ayah"] = $chapters;
                    // $section["content"] = $element->nodeValue;
                    // $section["paraghraphe"]=$topicList;
                    $sections[]=$topicList;
                    $csv[]=$ayahTable;

                    
                    
                    
                }
                // foreach ($elements  as $key => $element) {
                //     $chapters = array();
                //     $topicList=array();
                    
                //     $ayahs = $xpath->query("./tei:head/tei:quote", $element);
                //     $topics = $xpath->query("./tei:p", $element);
                    
                //     foreach ($ayahs as $value) {
                //         $number = $value->getattribute("n");
                //         $title = $value->nodeValue;
                //         $chapters = array("chapterNumber"=>explode(":", $number)[0],"ayahNumber" => $number, "title" => $title);
                //     }
                //     foreach ($topics  as $key => $topic) {
                        
                //         $type = $topic->getattribute("n");
                //         $list = $topic->getattribute("ana");
                //         $subtopics = $xpath->query("./tei:seg", $topic);
                //         $subtopicList=array();
                //         foreach ($subtopics as $key => $value) {
                //             $analist = $value->getattribute("ana");
                //             $subtopicList[] = array("text"=>$value->nodeValue,'subtopics' => $analist);
                //         }
                //         $topicList[] = array("xml_text"=> $doc->saveHTML($topic),"text"=>$topic->nodeValue,"type"=> $type,'topic' => $list,'subtopic' => $subtopicList);
                //     }
                //     $section = array();
                //     $section["ayah"] = $chapters;
                //     $section["content"] = $element->nodeValue;
                //     $section["paraghraphe"]=$topicList;
                //     $sections[]=$section;
                    

                // }

                  
                // $json = preg_replace('/(\s+)?\\\t(\s+)?/', ' ', json_encode($sections, JSON_UNESCAPED_UNICODE));
                // $json = preg_replace('/(\s+)?\\\n(\s+)?/', ' ',$json);
         
            }
            
            return  json_decode($json);
        }
    }
}
