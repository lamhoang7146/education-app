<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'name',
        'description',
        'google_drive_id',
        'status'
    ];

    public function contentItems()
    {
        return $this->morphMany(CoursesContentItem::class, 'content', 'content_type', 'content_id');
    }

}
