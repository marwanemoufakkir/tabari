<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Ayah;
class Surah extends Model
{


    /**
     * A surah has many ayah(s)
     * @return \Devtical\Quran\Models\Ayah
     */
    public function ayahs()
    {
        return $this->hasMany(Ayah::class);
    }


}