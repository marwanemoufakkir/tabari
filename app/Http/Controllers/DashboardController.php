<?php

namespace App\Http\Controllers;
use App\Models\Topic;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchPhraseQuery;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Highlight\Highlight;
use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\FiltersAggregation;



class DashboardController extends Controller
{
    protected $elasticsearch;

    //Set up our client
    public function __construct()
    {
        $this->elasticsearch = ClientBuilder::create()->build();

    }
    function topicsBubble(Request $request){

        $aggregation=array();
        $topics=Topic::get('tag');
        
        $boolQuery=new BoolQuery();
        foreach ($topics->toArray() as $key => $value) {
            if(!empty($value['tag'])){
                $aggregation[$value['tag']]=new TermQuery('topic',  $value['tag']);

            }
        }
        
        $filterAggregation = new FiltersAggregation(
            'topics',
            $aggregation
        );
        $parentHadithFilterAggregation = new FiltersAggregation(
            'hadith',
            [
                'type' => new TermQuery('type',  'hadith'),
            ]
            
        );
        $parentNotHadithFilterAggregation = new FiltersAggregation(
            'nothadith',
            [
                'type' => new TermQuery('type',  'nothadith'),
            ]
            
        );
        $parentHadithFilterAggregation->addAggregation($filterAggregation);
        $parentNotHadithFilterAggregation->addAggregation($filterAggregation);
        $search = new Search();
        $search->addAggregation($parentHadithFilterAggregation);
        $search->addAggregation($parentNotHadithFilterAggregation);
        
        if(isset($request->surah) and !empty($request->surah)){
            $boolQuery->add(new TermQuery('chapter',  $request->surah));
            $search->addQuery($boolQuery, BoolQuery::FILTER);
        }

        $queryArray = $search->toArray();
        $searchParams = [
            'index' => 'my-tafsir2',
            'body' => $queryArray,
        ];
        
        $items = $this->elasticsearch->search($searchParams);
        
        return response()->json(['data' => $this->buildCollection($items)],201);

    }
    function subtopicsBubble(Request $request){

        $aggregation=array();
        $topics=Topic::get('tag');
        
        $boolQuery=new BoolQuery();
        foreach ($topics->toArray() as $key => $value) {
            if(!empty($value['tag'])){
                $aggregation[$value['tag']]=new TermQuery('subtopic',  $value['tag']);

            }
        }
        
        $filterAggregation = new FiltersAggregation(
            'subtopics',
            $aggregation
        );
        $parentHadithFilterAggregation = new FiltersAggregation(
            'hadith',
            [
                'type' => new TermQuery('type',  'hadith'),
            ]
            
        );
        $parentNotHadithFilterAggregation = new FiltersAggregation(
            'nothadith',
            [
                'type' => new TermQuery('type',  'nothadith'),
            ]
            
        );
        $parentHadithFilterAggregation->addAggregation($filterAggregation);
        $parentNotHadithFilterAggregation->addAggregation($filterAggregation);
        $search = new Search();
        $search->addAggregation($parentHadithFilterAggregation);
        $search->addAggregation($parentNotHadithFilterAggregation);
        
        if(isset($request->surah) and !empty($request->surah)){
            $boolQuery->add(new TermQuery('chapter',  $request->surah));
            $search->addQuery($boolQuery, BoolQuery::FILTER);
        }

        $queryArray = $search->toArray();
        $searchParams = [
            'index' => 'my-tafsir2',
            'body' => $queryArray,
        ];
        
        $items = $this->elasticsearch->search($searchParams);
        
        return response()->json(['data' => $this->buildCollection($items)],201);

    }
    private function buildCollection(array $items)
    {
        return $items['aggregations'];
    }
}
