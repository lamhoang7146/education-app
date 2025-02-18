<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesController;
Route::get("/courses",[CoursesController::class,'index'])->name('courses.index');
