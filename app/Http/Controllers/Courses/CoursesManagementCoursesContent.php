<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\CoursesContent;
use App\Models\CoursesContentItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CoursesManagementCoursesContent extends Controller
{
    private array $data = [];
    public function index($id){
        $this->data['courses_id'] = $id;
        // Get course contents
        $this->data['courses_contents'] = CoursesContent::where('courses_id', $id)
            ->with(['contentItems' => function($query) {
                $query->with('content'); // This will load either Quiz or Video
            }])
            ->get();
        return Inertia::render('CoursesManagementCoursesContent/CoursesContent', [
            ...$this->data,
            'message' => session('message'),
            'status' => session('status')
        ]);
    }
    public function store($id){
        $data = request()->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('courses_contents')->where(function ($query) use ($id) {
                    return $query->where('courses_id', $id);
                })
            ],
            'status' => ['boolean'],
        ]);
        $name = CoursesContent::create([...$data,'courses_id'=>$id])->name;
        $this->with = back()->with([
            'message' => "Content {$name} has been added successfully",
            'status' => true
        ]);
    }
    public function update($id){
        $data = request()->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('courses_contents')->ignore(request()->id)->where(function ($query) use ($id) {
                    return $query->where('courses_id', $id);
                })
            ],
            'status' => ['boolean'],
        ]);
        $name = tap(CoursesContent::find(request()->id))->update($data)->name;
        $this->with = back()->with([
            'message' => "Content {$name} has been updated successfully",
            'status' => true
        ]);
    }
}
