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
    public function content()
    {
        // Make sure the morph map is using the correct case
        return $this->morphTo('content', 'content_type', 'content_id');
    }

    public function coursesContent()
    {
        return $this->belongsTo(CoursesContent::class, 'courses_content_id');
    }
}
