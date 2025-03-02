<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CoursesController extends Controller
{
    public function index()
    {
        return Inertia::render('Courses/List', []);
    }
    public function detail($id){
        return Inertia::render('Courses/Detail', []);
    }
    public function learning($courses_id){
        return Inertia::render('Courses/Learning', []);
    }
}
