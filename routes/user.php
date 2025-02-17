<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Route::middleware(['auth', 'twoStepVerification'])->group(function () {
    Route::get('/user/list', [UserController::class, 'index'])->name('user.list');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::post('/user/status/{user}', [UserController::class, 'status'])->name('user.status');
    Route::post('/user/update/{user}', [UserController::class, 'update'])->name('user.update');
//});

