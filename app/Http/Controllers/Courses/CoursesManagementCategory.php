<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Category_courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
class CoursesManagementCategory extends Controller
{
    private array $data = [];

    public function category()
    {
        $this->data['category_courses'] = Category_courses::all();
        return Inertia::render('CoursesManagementCategory/Category', [...$this->data,...[
            'message'=>session('message'),
            'status'=>session('status'),
        ]]);
    }

    public function storeCategory()
    {
        $user_id = Auth::user()->id;
        $credentials = request()->validate([
            'name' => 'required|string|max:255|min:3|unique:category_courses',
            'description' => 'required|string|max:255|min:3',
            'status' => 'required',
        ]);
        $category_courses_name = Category_courses::create([...$credentials,'user_id'=>$user_id])->name;
        return back()->with([
            'message' => "Category $category_courses_name created successfully",
            'status' => true
        ]);
    }
    public function updateCategory(Category_courses $category_courses){
        $credentials = request()->validate([
            'name' => ['required','string','max:255','min:3',Rule::unique(Category_courses::class)->ignore($category_courses->id)],
            'description' => 'required|string|max:255|min:3',
            'status' => 'required',
        ]);
        $category_name = $credentials['name'];
        $category_courses->update([
            'name'=>$credentials['name'],
            'description'=>$credentials['description'],
            'status'=>$credentials['status'],
        ]);
        return back()->with([
            'message' => "Category $category_name updated successfully",
            'status' => true
        ]);
    }
}
