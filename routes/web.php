<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

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
