<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use League\Csv\Reader;
use Sushi\Sushi;
use App\Models\Surah;


class Ayah extends Model
{
    use Sushi;

    /**
     * Setup schema for columns
     */
    protected $schema = [
        'id'           => 'integer',
        'surah_id'     => 'integer',
        'number'      =>   'string'
    ];

    /**
     * A surah belong to ayah
     * @return \Devtical\Quran\Models\Surah
     */
    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }

    /**
     * Process data via CSV
     * @return array
     */
    public function getRows()
    {
        $records = Reader::createFromPath(__DIR__ . '/../../resources/fixtures/ayah.csv', 'r')
            ->setHeaderOffset(0)
            ->getRecords();

        return collect($records)
            ->values()
            ->toArray();
    }
}