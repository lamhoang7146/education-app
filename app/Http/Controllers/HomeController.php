<?php

namespace App\Http\Controllers;

use App\Models\Category_courses;
use App\Models\Courses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    private array $data = [];
    public function index(){
        $this->data['categories'] = Category_courses::select('id','name')->where('status',1)->get();
        $this->data['popularCourses'] = Courses::select('courses.*')
            ->withCount('userCourses')
            ->orderBy('user_courses_count', 'desc')
            ->limit(8)
            ->get();
        return Inertia::render('Home',$this->data);
    }
}
