<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateMyIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        $mapping = [
            "properties"=> [
                "ayah"=> [
                  "type"=> "object",
                  "properties"=> [
                    "chapterNumber"=> [
                      "type"=> "long"
                   ],
                    "ayahNumber"=> [
                      "type"=> "keyword"
                   ],
                    "title"=> [
                      "type"=> "text",
                      "analyzer"=>"autocomplete_arabic"
                   ]
                 ]
               ],
                "content"=> [
                  "type"=> "text",
                  "analyzer"=>"arabic_synonym_normalized"
               ],
                "paraghraphe"=> [
                  "type"=> "nested",
                  "properties"=> [
                    "text"=> [
                      "type"=> "text"
                   ],
                    "type"=> [
                      "type"=> "text"
                   ],
                    "topic"=> [
                      "type"=> "text"
                   ]
                 ]
               ]
             ]
        ];
        
        $settings = [
            'number_of_replicas' => 2,
            "analysis"=> [
                "filter"=> [
                  "autocomplete"=> [
                    "type"=> "edge_ngram",
                    "min_gram"=> 1,
                    "max_gram"=> 30   
                 ],
                  "arabic_stop"=> [
                    "type"=>       "stop",
                    "stopwords"=>  "_arabic_" 
                 ],
                  "arabic_keywords"=> [
                    "type"=>       "keyword_marker",
                    "keywords"=>   ["ياسين","موسي","عيسي","يوسف","ابراهيم","اسماعيل","نوح"] 
                 ],
                  "arabic_stemmer"=> [
                    "type"=>       "stemmer",
                    "language"=>   "arabic"
                 ]
          
               ],
                "analyzer"=> [
                    "autocomplete_arabic"=>[
                      "type"=> "custom",
                      "tokenizer"=> "whitespace",
                      "filter"=>[
                        "arabic_normalization",
                        "autocomplete",
                        "arabic_keywords"
                      ]
                   ],
                  "rebuilt_arabic"=> [
                    "tokenizer"=>  "standard",
                    "filter"=> [
                      "lowercase",
                      "decimal_digit",
                      "arabic_stop",
                      "arabic_normalization",
                      "arabic_keywords",
                      "arabic_stemmer"
                    ]
                 ],
                    "arabic_synonym_normalized"=>[
                      "tokenizer"=>  "icu_tokenizer",
                      "filter"=> [
                        "arabic_keywords",
                        "arabic_normalization",
                       "arabic_stemmer",
                       "arabic_stop",
                       "icu_folding"
                      ]
                   ]
               ]
             ]
        ];
        Index::createIfNotExistsRaw('my-index', $mapping, $settings);

    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        //
    }
}
