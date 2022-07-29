<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use League\Csv\Reader;
use App\Models\Surah;
class SurahController extends Controller
{
    public function getRows()
    {
        $records = Reader::createFromPath('/var/www/html/tafsires/resources/fixtures/surah.csv', 'r')
            ->setHeaderOffset(0)
            ->getRecords();

        $arr=collect($records)
            ->values()
            ->toArray();
        Surah::create($arr);
        }
}
