<?php

namespace App\Http\Controllers;

use App\Models\Ayah;
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

            // dd($param);
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
        $searchParams = [
            'index' => 'my-tafsir2',
            'from' => $from,
            'size' => $per_page,
            'body' => $search->toArray(),
        ];
        $searchParams['sort'] = array('timestamp:asc');
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

}
