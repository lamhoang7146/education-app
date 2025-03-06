<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses\CoursesManagementCategory;
Route::get("/courses-management-category",[CoursesManagementCategory::class,'category'])->name('courses.management.category');
Route::post('/courses-management/category-add',[CoursesManagementCategory::class,'storeCategory'])->name('courses.management.category.add');
Route::post('/courses-management/category-update/{category_courses}',[CoursesManagementCategory::class,'updateCategory'])->name('courses.management.category.update');
