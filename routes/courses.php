<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesController;
Route::get("/courses",[CoursesController::class,'index'])->name('courses.index');
Route::get("/courses/{id}",[CoursesController::class,'detail'])->name('courses.detail');
Route::get("/courses/{courses_id}/learning",[CoursesController::class,'learning'])->name('courses.learning');
