<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesController;

Route::get("/courses",[CoursesController::class,'index'])->name('courses.index');
Route::get('/courses/find-courses-by-ai',[CoursesController::class,'coursesByAI'])->name('courses.find-ai');
Route::post('/courses/send-find-courses-by-ai',[CoursesController::class,'handleCoursesByAI'])->name('courses.send.find-ai');
Route::get("/courses/{id}",[CoursesController::class,'detail'])->name('courses.detail');
Route::get("/purchased-courses",[CoursesController::class,'purchased'])->name('courses.purchased');
Route::get("/courses/{id}/type/{type}/learning/{content_id}",[CoursesController::class,'learning'])->name('courses.learning');
Route::post('/quiz/submit/{id}', [CoursesController::class,'submitQuiz'])->name('quiz.submit');
Route::post('/quiz/reset/{id}',[CoursesController::class,'resetQuiz'])->name('quiz.reset');
