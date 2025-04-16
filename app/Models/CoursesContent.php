<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursesContent extends Model
{
    protected $fillable = [
      'name',
      'status',
      'courses_id',
        'position'
    ];
    public function courses()
    {
        return $this->belongsTo(Courses::class, 'courses_id');
    }
    public function contentItems()
    {
        return $this->hasMany(CoursesContentItem::class, 'courses_content_id');
    }
}
