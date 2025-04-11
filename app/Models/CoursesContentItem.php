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
        return $this->morphTo('content', 'content_type', 'content_id');
    }

    public function coursesContent()
    {
        return $this->belongsTo(CoursesContent::class, 'courses_content_id');
    }
    public function video()
    {
        return $this->belongsTo(Video::class, 'content_id')->where('content_type', 'video');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'content_id')->where('content_type', 'quiz');
    }
}
