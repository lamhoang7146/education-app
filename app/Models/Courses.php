<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y'); // Format dd/mm/yyyy
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function categoryCourses(){
        return $this->belongsTo(Category_courses::class);
    }
    public function coursesContents()
    {
        return $this->hasMany(CoursesContent::class, 'courses_id');
    }
    public function scopeFilter($query, array $categoryCoursesId)
    {
        if (isset($categoryCoursesId[0]) && $categoryCoursesId[0] !== null) {
            return $query->where('category_courses_id', $categoryCoursesId[0]);
        }
        return $query;
    }
}
