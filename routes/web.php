<?php

use App\Http\Controllers\YoutubeOauthController;
use App\Http\Controllers\YoutubeUploadController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/youtube/access-token', [YoutubeOauthController::class, 'getAccessToken']);
Route::post('/youtube/update-token', [YoutubeOauthController::class, 'updateAccessToken']);
Route::get('/youtube/authenticate', [YoutubeOauthController::class, 'authenticate']);
Route::get('/youtube/callback', [YoutubeOauthController::class, 'callback']);
Route::get('/youtube/upload', [YoutubeUploadController::class, 'showUploadForm']);
Route::post('/youtube/upload', [YoutubeUploadController::class, 'uploadVideo'])->name('youtube.upload');

Route::get('/youtube/upload-form', function () {
    return Inertia::render('Youtube/Upload');
})->name('youtube.upload-form');

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
