<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesManagementCoursesContentItem;
Route::get('/courses-management/courses-content-add/{courses_id}/item/{id}',[CoursesManagementCoursesContentItem::class,'index'])->name('courses.management.courses.content.item');
