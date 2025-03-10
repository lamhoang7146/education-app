<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Category_courses;
use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CoursesManagementCourses extends Controller
{
    private array $data = [];

    public function courses()
    {
        $this->data['category_courses'] = Category_courses::select('id', 'name')->where('status', true)->get();
        $this->data['courses'] = Courses::with(['user','categoryCourses'])
            ->select("*")
            ->paginate(8)
            ->withQueryString();
        return Inertia::render('CoursesManagementCourses/Courses', [
            ...$this->data,
            'message' => session('message'),
            'status' => session('status')
        ]);
    }

    public function storeCourses()
    {
        $user_id = Auth::user()->id;
        $credentials = request()->validate([
            'title' => 'required|min:3',
            'description' => 'required|min:20',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'isFree' => 'nullable|boolean',
            'price' => 'required_if:isFree,false|min:6|max:7|nullable',
            'category_courses_id' => 'required',
            'selectedLevel' => 'required',
            'status' => 'required',
        ]);
        $credentials['thumbnail'] = Storage::disk('public')->put('courses', request()->file('thumbnail'));
        $title = Courses::create([
            'title' => $credentials['title'],
            'description' => $credentials['description'],
            'thumbnail' => $credentials['thumbnail'],
            'is_free' => !empty($credentials['isFree']),
            'price' => empty($credentials['isFree']) ? $credentials['price'] : null,
            'category_courses_id' => $credentials['category_courses_id']['id'],
            'level' => $credentials['selectedLevel']['name'],
            'status' => $credentials['status'],
            'user_id' => $user_id,
        ])->title;
        return back()->with(['message' => "Courses $title created successfully", 'status' => true]);
    }
    public function updateCourses(Courses $courses)
    {
        $baseValidation = [
            'title' => 'required|min:3',
            'description' => 'required|min:20',
            'isFree' => 'nullable|boolean',
            'price' => 'required_if:isFree,false|min:6|max:7|nullable',
            'category_courses_id' => 'required',
            'selectedLevel' => 'required',
            'status' => 'required',
        ];

        // Check if a new thumbnail was uploaded
        if (request()->hasFile('thumbnail')) {
            $baseValidation['thumbnail'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        $credentials = request()->validate($baseValidation);

        // Handle the thumbnail
        if (request()->hasFile('thumbnail')) {
            // Delete old thumbnail if it exists
            if ($courses->thumbnail) {
                Storage::disk('public')->delete($courses->thumbnail);
            }

            // Store the new thumbnail
            $credentials['thumbnail'] = Storage::disk('public')->put('courses', request()->file('thumbnail'));
        }

        // Update the course
        $courses->update([
            'title' => $credentials['title'],
            'description' => $credentials['description'],
            'thumbnail' => $credentials['thumbnail'] ?? $courses->thumbnail, // Keep existing if no new upload
            'is_free' => !empty($credentials['isFree']),
            'price' => empty($credentials['isFree']) ? $credentials['price'] : null,
            'category_courses_id' => $credentials['category_courses_id']['id'],
            'level' => $credentials['selectedLevel']['name'],
            'status' => $credentials['status']
        ]);

        return back()->with(['message' => "Course '{$courses->title}' updated successfully", 'status' => true]);
    }
}
