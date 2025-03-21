<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'price',
        'user_id',
        'category_courses_id',
        'is_free',
        'level',
        'status'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function categoryCourses(){
        return $this->belongsTo(Category_courses::class);
    }
    public function scopeFilter($query, array $categoryCoursesId)
    {
        if (isset($categoryCoursesId[0]) && $categoryCoursesId[0] !== null) {
            return $query->where('category_courses_id', $categoryCoursesId[0]);
        }
        return $query;
    }
}
