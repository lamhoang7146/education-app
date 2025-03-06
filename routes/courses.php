<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesController;
use App\Http\Controllers\Courses\CoursesManagementController;
Route::get("/courses",[CoursesController::class,'index'])->name('courses.index');
Route::get("/courses/{id}",[CoursesController::class,'detail'])->name('courses.detail');
Route::get("/courses/{courses_id}/learning",[CoursesController::class,'learning'])->name('courses.learning');

Route::get("/courses-management",[CoursesManagementController::class,'index'])->name('courses.management');
