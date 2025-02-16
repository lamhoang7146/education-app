<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;

Route::get('/role/list', [RoleController::class, 'list'])->name('role.list');
Route::post('/role/add', [RoleController::class, 'add'])->name('role.add');

Route::post('/role/update/{id}', [RoleController::class, 'update'])->name('role.update');
