<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz_content_detail extends Model
{
    protected $fillable = [
        'question',
        'answers',
        'result',
        'quiz_id',
    ];
}
