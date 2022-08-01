<?php

namespace App\Http\Controllers;

use App\Models\Ayah;
use App\Models\Surah;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchPhraseQuery;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;

use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Highlight\Highlight;
use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\Query\Specialized\MoreLikeThisQuery;



class ClientController extends Controller
{



    protected $elasticsearch;

    //Set up our client
    public function __construct()
    {
        $this->elasticsearch = ClientBuilder::create()->build();

    }
    public function elasticsearchQueries(Request $request){
        $query='';
        if($request->kt_docs_repeater_advanced[0]['content']!=''){
            $query=$request->kt_docs_repeater_advanced[0]['content'];
        }
        
        $items=$this->searchOnElasticsearchBool($request);
        // dd($items);
        return view('results')->with('result',$this->buildCollection($items))->with('count',$this->getTotal($items))->with('query',$query);
    }
    public function fetchAyah(Request $request)
    {
        $items = $this->findAyahXML($request->id);
        // dd($items);
        return view('ayah')->with('result',$items['_source']);
    }
    public function fetchSurahAyah(Request $request)
    {
        $ayah=Ayah::get()->where('surah_id',$request->surah_id);
        return response()->json(['data' => $ayah],201);
    }


    private function findAyahXML(string $query = ''): array
    {
        $params = [
            'index' => 'my-tafsir',
            'id'    => $query
        ];
        
        $items = $this->elasticsearch->get($params);

        return $items;
    }



    private function searchOnElasticsearchBool(Request $request)
    {
        


        $requestParams = $request->kt_docs_repeater_advanced;
        $search = new Search();
        $boolQuery=new BoolQuery();
  
        
        foreach ($requestParams as $key => $param) {

            $param=array_filter($param);
            $searchType=$param['search_type'];
            if(isset($param['bool'])){
                $boolOperator=$param['bool'];   
            }else{
                $boolOperator='MUST';

            }
            switch ($boolOperator) {
                case 'MUST':
                    $BoolQueryOperator=BoolQuery::MUST;
                    break;
                case 'SHOULD':
                    $BoolQueryOperator=BoolQuery::SHOULD;
                    break;
                default:
                $BoolQueryOperator=BoolQuery::MUST_NOT;
                    break;
            }

            // dd($param);
            foreach ($param as $key => $field) {
                if($key === 'content' ){                    
                    switch ($searchType) {
                        case 'default':
                            $boolQuery->add(new MatchQuery('content.rebuilt_arabic', $field), $BoolQueryOperator);

                            break;
                        case 'exact':
                            $boolQuery->add(new MatchPhraseQuery('content', $field), $BoolQueryOperator);

                            break;
                        case 'synonym':
                            $newTER=new MatchQuery('content', $field);
                            $newTER->addParameter('analyzer','arabic_synonym_normalized');
                            $boolQuery->add($newTER, $BoolQueryOperator);
                                // $boolQuery
                            break;
                        default:
                            // $moreLikeThisQuery = new MoreLikeThisQuery(
                            //     $field,
                            //     [
                            //         'fields' => ['content'],
                            //         'min_term_freq' => 1,
                            //         'max_query_terms' => 10,
                            //     ]
                            // );
                            
                            // $boolQuery->add($moreLikeThisQuery, $BoolQueryOperator);
                            $boolQuery->add(new MatchQuery('content.boolean_sim_field', $field), $BoolQueryOperator);

                            break;
                    }

                }

                if ($key === 'ayah' || $key === 'chapter' ||  $key === 'topic' || $key === 'subtopic' || $key === 'type') {
                    $boolQuery->add(new TermQuery($key, $field),$BoolQueryOperator );


                }
            }





            
        }
        $search->addQuery($boolQuery);


        $requestFilter = $request->filter;

        if(isset($requestFilter)){
            foreach ($requestFilter as $key => $value) {
            
                if( $key === 'surah'){
                    foreach ($value as $key => $term) {
                        $filter = new TermQuery('chapter',  $term);
                        $search->addQuery($filter, BoolQuery::FILTER);
                        
                    }
    
                }
                if( $key === 'type'){
                    foreach ($value as $key => $term) {
                        $filter = new TermQuery('type',  $term);
                        $search->addQuery($filter, BoolQuery::FILTER);
                        
                    }
    
                }
                if( $key === 'topic'){
                    foreach ($value as $key => $term) {
                        $filter = new TermQuery('topic',  $term);
                        $search->addQuery($filter, BoolQuery::FILTER);
                        
                    }
    
                }
                if( $key === 'subtopic'){
                    foreach ($value as $key => $term) {
                        $filter = new TermQuery('subtopic',  $term);
                        $search->addQuery($filter, BoolQuery::FILTER);
                        
                    }
    
                }
            }
        }


        $higlight = new Highlight();
        $higlight->addField('content');
        $higlight->addField('content.arabic_synonym_normalized');
        $higlight->addField('content.rebuilt_arabic');
        $higlight->addField('content.boolean_sim_field');

        $higlight->setTags(["<a class='ls-2 fw-bolder' style='text-decoration: underline;'>"],["</a>"]);
        // $higlight->setFragmentSize(0);
        // $higlight->setNumberOfFragments(2);
        $search->addHighlight($higlight);
        $searchParams = [
            'index' => 'my-tafsir',
            'from'=>0,
            'size'=>10,
            'body' => $search->toArray(),
        ];
        $searchParams['sort'] = array('timestamp:asc');
        $items = $this->elasticsearch->search($searchParams);
        // $indices = $this->elasticsearch->cat()->indices(array('index' =>'my-tafsir'));
        // dd($indices[0]);
         return $items;
    
        

    }
    private function cleanupQuery(string $query_string,$BoolQueryOperator){
        $query_string=str_replace($BoolQueryOperator.'(',$BoolQueryOperator.' (',$query_string);
        $query_string=str_replace('(AND ','(',$query_string);
        $query_string=str_replace('( AND ','(',$query_string);
        $words = explode( " ", $query_string );
        array_splice( $words, -1 );
        $query_string=implode( " ", $words );
        $query_string=str_replace('()','(*)',$query_string);
        return $query_string;
    }
    private function buildCollection(array $items)
    {
        return $items['hits']['hits'];
    }
    private function getTotal(array $items){
        $count=$items['hits']['total'];
        return $count;
    }

}


