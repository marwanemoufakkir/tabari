<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;


class Subtopic extends Model
{
    use HasFactory;

    public function topics()
    {
        return $this->belongsTo(Topic::class);
    }
}
