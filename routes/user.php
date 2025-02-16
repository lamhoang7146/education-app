<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'twoStepVerification'])->group(function () {
    Route::get('/user/list', [UserController::class, 'index'])->name('user.list');
    Route::get('/user/show', [UserController::class, 'show'])->name('user.show');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::post('/user/status/{user}', [UserController::class, 'status'])->name('user.status');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/update/{user}', [UserController::class, 'update'])->name('user.update');
});

