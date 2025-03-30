<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesManagementQuizDetail;

Route::get('/course-management-quiz/detail/{quiz}', [CoursesManagementQuizDetail::class, 'index'])->name('courses.management.quiz.detail');
Route::post('/course-management-quiz/detail/add', [CoursesManagementQuizDetail::class, 'store'])->name('courses.management.quiz.detail.add');
Route::post('/course-management-quiz/detail/generate-with-ai', [CoursesManagementQuizDetail::class, 'generateWithAI'])
    ->name('courses.management.quiz.detail.generate');
Route::put('/course-management-quiz/detail/update/{id}', [CoursesManagementQuizDetail::class, 'update'])->name('courses.management.quiz.detail.update');
Route::delete('/course-management-quiz/detail/delete/{id}', [CoursesManagementQuizDetail::class, 'destroy'])->name('courses.management.quiz.detail.delete');

