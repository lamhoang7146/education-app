<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CoursesManagementController extends Controller
{
    private array $data;
    public function index(){
        return Inertia::render('Courses/CoursesManagement/Index', []);
    }
}
