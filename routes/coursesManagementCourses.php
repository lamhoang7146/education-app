<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesManagementCourses;
Route::get("/courses-management-courses",[CoursesManagementCourses::class,'courses'])->name('courses.management.courses');
Route::post('/courses-management/courses-add',[CoursesManagementCourses::class,'storeCourses'])->name('courses.management.courses.add');
Route::post('/courses-management/courses-update/{courses}',[CoursesManagementCourses::class,'updateCourses'])->name('courses.management.courses.update');
