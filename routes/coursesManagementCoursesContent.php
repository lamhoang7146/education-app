<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesManagementCoursesContent;
Route::get("/courses-management-courses-content/{id}",[CoursesManagementCoursesContent::class,'index'])->name('courses.management.courses.content');
Route::post('/courses-management/courses-content-add/{id}',[CoursesManagementCoursesContent::class,'store'])->name('courses.management.courses.content.add');
Route::post('/courses-management/courses-content-update/{id}',[CoursesManagementCoursesContent::class,'update'])->name('courses.management.courses.content.update');
