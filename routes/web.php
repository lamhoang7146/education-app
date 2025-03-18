<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\YouTubeController;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/courses.php';
require_once __DIR__ . '/roles.php';
require_once __DIR__ . '/user.php';

Route::post('/get-data', function () {
    dd(request()->all());
})->name('send.data');

Route::get('/api/youtube-channel-id', [YouTubeController::class, 'getChannelId']);
Route::get('/api/youtube-videos', [YouTubeController::class, 'getVideos']);

// Add this route to render the Youtube.vue component
Route::get('/youtube', function () {
    return Inertia::render('Youtube/Youtube');
})->name('youtube');
