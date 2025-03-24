<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\YouTubeController;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/upload-video-form', function () {
    return Inertia::render('UploadVideo/UploadVideo');
})->name('upload-video-form');

Route::post('/api/upload-video', [YouTubeController::class, 'uploadVideo'])->name('api.upload-video');
Route::get('/oauth2callback', [YouTubeController::class, 'oauth2callback'])->name('oauth2callback');

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/courses.php';
require_once __DIR__ . '/roles.php';
require_once __DIR__ . '/user.php';
require_once __DIR__ . '/coursesManagement.php';
require_once __DIR__ . '/coursesManagementCategory.php';
require_once __DIR__ . '/coursesManagementCourses.php';
require_once __DIR__ . '/coursesManagementVideo.php';
require_once __DIR__ . '/coursesManagementQuiz.php';
require_once __DIR__ . '/coursesManagementQuizDetail.php';
