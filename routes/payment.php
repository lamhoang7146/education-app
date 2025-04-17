<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\PaymentController;

Route::middleware('auth')->group(function() {
    Route::get('/courses-payment/{courses}', [PaymentController::class, 'index'])->name('courses.payment');
    Route::get('/courses/handle-update-payment/{courses}', [PaymentController::class, 'handleUpdatePayment']);
});
