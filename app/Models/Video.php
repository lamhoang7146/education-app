<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title',
        'description',
        'youtube_id',
        'status'
    ];
    // Define relationship to course content items
    public function contentItems()
    {
        return $this->morphMany(CoursesContentItem::class, 'content', 'content_type', 'content_id');
    }
}
