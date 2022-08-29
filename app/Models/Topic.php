<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Subtopic;


class Topic extends Model
{
    public function subtopics()
    {
        return $this->hasMany(Subtopic::class);
    }
}