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
    public function category() {
        return $this->belongsTo(Category_courses::class, 'category_courses_id');
    }
    public function categoryCourses(){
        return $this->belongsTo(Category_courses::class);
    }
    public function coursesContents()
    {
        return $this->hasMany(CoursesContent::class, 'courses_id');
    }
    public function scopeFilter($query, array $categoryCoursesId,$search = null)
    {
        if (isset($search) && isset($categoryCoursesId[0]) && $categoryCoursesId[0] !== null) {
            // Nếu cả hai điều kiện đều có
            return $query->where('category_courses_id', $categoryCoursesId[0])
                ->where('title', 'like', "%".$search."%");
        } elseif (isset($search)) {
            // Chỉ kiểm tra điều kiện search
            return $query->where('title', 'like', "%".$search."%");
        } elseif (isset($categoryCoursesId[0]) && $categoryCoursesId[0] !== null) {
            // Chỉ kiểm tra điều kiện category_courses_id
            return $query->where('category_courses_id', $categoryCoursesId[0]);
        }

        return $query;
    }
    public function userCourses() {
        return $this->hasMany(user_courses::class, 'courses_id');
    }
}
