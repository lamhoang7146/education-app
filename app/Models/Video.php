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
    public function contentItem()
    {
        return $this->morphOne(CoursesContentItem::class, 'content');
    }
}
