<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CoursesManagementCoursesContentItem extends Controller
{
    private $data = [];
    public function index($courses_id, $id)
    {
        $this->data['courses_id'] = (int) ($courses_id);
        $this->data['content_item_id'] = (int) $id;
        return Inertia::render('CoursesManagementCoursesContentItem/CoursesContentItem',[...$this->data]);
    }
}
