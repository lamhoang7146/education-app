<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursesContentItem extends Model
{
    protected $fillable = [
        'courses_content_id',
        'content_type',
        'content_id',
        'status'
    ];
}
