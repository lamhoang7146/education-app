<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiAnalyticsController;

Route::get('/ai-analytics',[AiAnalyticsController::class,'index'])->name('ai.analytics');
