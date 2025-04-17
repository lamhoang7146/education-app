<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;

Route::get('/analytics',[AnalyticsController::class,'index'])->name('analytics');
