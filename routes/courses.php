<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesController;

Route::get("/courses",[CoursesController::class,'index'])->name('courses.index');
Route::get("/courses/{id}",[CoursesController::class,'detail'])->name('courses.detail');
Route::get("/courses/{id}/type/{type}/learning/{content_id}",[CoursesController::class,'learning'])->name('courses.learning');
Route::post('/quiz/submit', [CoursesController::class,'submitQuiz'])->name('quiz.submit');
Route::post('/quiz/reset',[CoursesController::class,'resetQuiz'])->name('quiz.reset');
