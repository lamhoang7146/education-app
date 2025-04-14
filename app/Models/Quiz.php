<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';
    protected $fillable = [
      'name',
      'status'
    ];
    public function contentItem()
    {
        return $this->morphOne(CoursesContentItem::class, 'content');
    }
    public function quizContentDetails()
    {
        return $this->hasMany(Quiz_content_detail::class, 'quiz_id');
    }
}
