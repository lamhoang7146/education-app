<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesManagementVideo;
Route::get("/courses-management-video",[CoursesManagementVideo::class,'video'])->name('courses.management.video');
Route::post('/courses-management/video-add',[CoursesManagementVideo::class,'storeVideo'])->name('courses.management.video.add');
Route::post('/courses-management/video-update/{video}',[CoursesManagementVideo::class,'updateVideo'])->name('courses.management.video.update');
Route::prefix('youtube')->group(function () {
    Route::get('/auth', [CoursesManagementVideo::class, 'authenticateYoutube'])->name('youtube.auth');
    Route::get('/callback', [CoursesManagementVideo::class, 'youtubeCallback'])->name('youtube.callback');
    Route::get('/check-auth', [CoursesManagementVideo::class, 'checkAuth'])->name('youtube.check-auth');
});
