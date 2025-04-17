<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerificationEmailController;
use App\Http\Controllers\Auth\TwoStepVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// TODO: Authentication

//------------------- Using middleware for guest -------------------//

Route::middleware('guest')->group(function () {

    //-------------------  Register -------------------//

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    //------------------- Login -------------------//

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/redirect-to-login', [LoginController::class, 'redirectToLogin'])->name('redirect.to.login');

    //------------------- Google -------------------//

    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google-auth');
    Route::get('/auth/google/call-back', [GoogleController::class, 'handleGoogleCallback']);

    //------------------- Forgot the password -------------------//

    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password')->middleware('throttle:6,1');

    //------------------- Reset the password -------------------//

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password/', [ResetPasswordController::class, 'handle'])->name('password.update');

    //------------------- Verification email -------------------//

    Route::get('/verify-email', [VerificationEmailController::class, 'show'])->name('verify-email');
    Route::get('/form-verify-email', [VerificationEmailController::class, 'form'])->name('form-verify-email');
    Route::post('/resend-email', [VerificationEmailController::class, 'resend'])->name('resend-email')->middleware('throttle:6,1');
    Route::get('/verify-email/{id}/{expired}', [VerificationEmailController::class, 'verify']);


});

//------------------- End -------------------//

//------------------- Using middleware for auth -------------------//

Route::middleware('auth')->group(function () {

    //------------------- Log out -------------------//

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    //------------------- Two step verification -------------------//
    Route::get('/two-step-verification', [TwoStepVerificationController::class, 'show'])->name('two-step-verification');
    Route::post('/two-step-verification', [TwoStepVerificationController::class, 'twoStepVerification'])->middleware('throttle:6,1')->name('two-step-verification');
    Route::get('/form-two-step-verification', [TwoStepVerificationController::class, 'form'])->name('form-two-step-verification');
    Route::post('/handle-two-step-verification', [TwoStepVerificationController::class, 'handle'])->name('handle-two-step-verification');
});

//------------------- End -------------------//
// TODO: END
