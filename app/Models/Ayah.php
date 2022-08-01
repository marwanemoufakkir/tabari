<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Surah;


class Ayah extends Model
{

 

    /**
     * A surah belong to ayah
     * @return \Devtical\Quran\Models\Surah
     */
    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }


}