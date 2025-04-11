<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\VideoController;

// Google Drive Authentication
Route::get('/google-drive/authenticate', [VideoController::class, 'authenticate'])->name('google-drive.authenticate');
Route::get('/google-drive/callback', [VideoController::class, 'callback'])->name('google-drive.callback');

Route::prefix('courses/management')->name('courses.management.')->group(function () {
    // Video routes
    Route::get('/course/{courses_id}/content-item/{content_item_id}/video/add-form', [VideoController::class, 'addForm'])
        ->name('video.add-form');

    // Add video to course content
    Route::post('/course/{courses_id}/content-item/{content_item_id}/video/add', [VideoController::class, 'addVideo'])
        ->name('video.add');

    // Resume upload after authentication
    Route::get('/course/{courses_id}/content-item/{content_item_id}/video/resume-upload', [VideoController::class, 'resumeUpload'])
        ->name('video.resume-upload');

    // List videos in a course
    Route::get('/course/{courses_id}/videos', [VideoController::class, 'index'])
        ->name('videos.index');

    // Edit specific video
    Route::get('/course/{courses_id}/video/{video_id}/edit', [VideoController::class, 'edit'])
        ->name('video.edit');

    // Update video
    Route::put('/course/{courses_id}/video/{video_id}', [VideoController::class, 'update'])
        ->name('video.update');

    // Delete video
    Route::delete('/course/{courses_id}/video/{video_id}', [VideoController::class, 'delete'])
        ->name('video.delete');
});
