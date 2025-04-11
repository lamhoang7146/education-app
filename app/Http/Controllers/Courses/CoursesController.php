<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Category_courses;
use App\Models\Courses;
use Inertia\Inertia;

class CoursesController extends Controller
{
    private array $data = [];
    public function index()
    {
        $this->data['courses'] = Courses::with([
            'user:id,name,image',
            'categoryCourses:id,name',
        ])->select('id', 'title', 'price', 'is_free', 'level', 'status', 'category_courses_id', 'user_id','thumbnail','created_at')
            ->filter([request('category_courses_id')])
            ->where('status', 1)
            ->paginate(10)->withQueryString();
        $this->data['category_courses'] = Category_courses::where('status', 1)
            ->select('id', 'name')
            ->get();
        return Inertia::render('Courses/List',$this->data);
    }

    public function detail($id){
        $course_detail = Courses::with([
            'categoryCourses',
            'user:id,name,image',
            'coursesContents' => function($query) {
                $query->where('status', 1)
                    ->with(['contentItems' => function($query) {
                        $query->where('status', 1)
                            ->with(['content']);
                    }]);
            }
        ])
            ->select('id', 'title', 'price', 'is_free', 'level', 'category_courses_id', 'user_id', 'thumbnail', 'description', 'created_at', 'status')
            ->findOrFail($id);

        // Count the total number of course contents
        $content_count = $course_detail->coursesContents->count();

        return Inertia::render('Courses/Detail', [
            'courses_detail' => $course_detail,
            'content_count' => $content_count
        ]);
    }
    public function learning($courses_id){
        return Inertia::render('Courses/Learning', []);
    }
}
