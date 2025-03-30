<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesManagementQuiz;
Route::post('/courses-management-quiz/{courses_id}/quiz-add/{content_item_id}', [CoursesManagementQuiz::class, 'store'])->name('courses.management.quiz.add');
Route::post('/courses-management-quiz/{quiz}/quiz-update/{content_item_id}', [CoursesManagementQuiz::class, 'update'])->name('courses.management.quiz.update');
