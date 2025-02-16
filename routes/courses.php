<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/test', function () {
    dd(\App\Models\Permission::getRecord());
});
