<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Backend\AdminCampusController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\GradeController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\MainCategoryController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\RoleControllerr;
use App\Http\Controllers\Backend\TeacherCampusController;
use App\Http\Controllers\Backend\TeacherGradeController;
use App\Http\Controllers\Backend\WebsitesetupController;
use App\Http\Controllers\ProfileController;
use App\Models\Websitesetup;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Permission;

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/backend/edit', [WebsitesetupController::class, 'edit'])->name('backend.edit');
    Route::post('/backend/update', [WebsitesetupController::class, 'update'])->name('websitesetup.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('main-category', MainCategoryController::class);

    Route::post('/store/permission', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/create/permission', [PermissionController::class, 'index'])->name('permissions.index');

    Route::put('/permission-update/{id}', [PermissionController::class, 'update'])->name('permissions.update');

    Route::get('permissions-edit/{id}', [PermissionController::class, 'edit'])->name('permissions.edit');

    Route::delete('permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    Route::post('/user/store', [RegisteredUserController::class, 'admin_user'])->name('user.store');

    Route::get('/users/status/{id}', [RegisteredUserController::class, 'update_status'])->name('user.status');

    Route::get('/create/user', [RegisteredUserController::class, 'admin_create'])->name('users.create');
    Route::get('/user/edit/{id}', [RegisteredUserController::class, 'edit'])->name('user.edit');

    Route::put('user/{id}', [RegisteredUserController::class, 'update'])->name('user.update');
    Route::delete('user/{id}', [RegisteredUserController::class, 'destroy'])->name('user.destroy');

    Route::resource('grades', controller: GradeController::class);

    Route::resource('teachergrades', controller: TeacherGradeController::class);
    // Add this route to your web.php
    Route::get('/teachergrades/{user_id}/', [TeacherGradeController::class, 'getSelectedGrades'])->name('teachergrades');


    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/{id}', [RoleController::class, 'permission'])->name('find.permissions');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::post('/roles/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign.permissions');
    Route::get('campus/edit/{id}', [LocationController::class, 'campus_edit'])->name('campus.edit');

    Route::post('update-campus', [LocationController::class, 'update_campus'])->name('update_campus');
    Route::any('update-edit/{id?}', [LocationController::class, 'update_campus'])->name('campus.update');
    Route::GET('/get-districts', [AdminCampusController::class, 'getDistricts'])->name('ajax.get-districts');
    Route::GET('/get-campuses', [AdminCampusController::class, 'getCampuses'])->name('ajax.get-campuses');

    Route::resource('location', LocationController::class);
    Route::resource('school-campus', AdminCampusController::class);
    Route::post('/course/status-update/{id}', [CourseController::class, 'updateStatus'])->name('course.status.update');

    // Route::resource('location', App\Http\Controllers\Backend\LessonController::class);
    Route::resource('course', App\Http\Controllers\Backend\CourseController::class);
    Route::resource('gradecategory', App\Http\Controllers\Backend\GradecategoryController::class);
});
