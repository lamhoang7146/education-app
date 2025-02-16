<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;

Route::get('/role/list', [RoleController::class, 'list'])->name('role.list');
Route::get('/role/show', [RoleController::class, 'show'])->name('role.show');
Route::post('/role/add', [RoleController::class, 'add'])->name('role.add');
Route::get('/role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
Route::post('/role/update/{id}', [RoleController::class, 'update'])->name('role.update');
