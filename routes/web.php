<?php
use Illuminate\Support\Facades\Route;

require_once __DIR__ . '/home.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/courses.php';
Route::middleware('twoStepVerification')->group(function () {
    require_once __DIR__ . '/roles.php';
    require_once __DIR__ . '/user.php';
    require_once __DIR__ . '/coursesManagementCategory.php';
    require_once __DIR__ . '/coursesManagementCourses.php';
    require_once __DIR__ . '/coursesManagementVideo.php';
    require_once __DIR__ . '/coursesManagementQuiz.php';
    require_once __DIR__ . '/coursesManagementQuizDetail.php';
    require_once __DIR__ . '/coursesManagementCoursesContent.php';
    require_once __DIR__ . '/coursesManagementCoursesContentItem.php';
    require_once __DIR__ . '/analytics.php';
    require_once __DIR__ . '/aiAnalytics.php';
});
