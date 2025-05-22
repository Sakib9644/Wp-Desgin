<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\MainCategoryController;

// Public route (no auth needed)
Route::post('/login', [AuthController::class, 'login']);



  Route::post('/reset-password', [AuthController::class, 'reset_password']);  
  Route::post('/password-update', [AuthController::class, 'update_password']);

  Route::post('/register', [AuthController::class, 'register']);

  Route::post('/verify-register', [AuthController::class, 'register_verify_otp']);
  Route::post('/resend-otp', [AuthController::class, 'resend_otp']);


// Grouped routes (JWT protected)
Route::middleware('jwt.verify')->group(function () {

    Route::get('/main-category', action: [MainCategoryController::class, 'index']);
    Route::get('/country', action: [AuthController::class, 'country']);
    Route::get('/district/{id}', action: [AuthController::class, 'district']);
    Route::get(uri: '/campus/{id}', action: [AuthController::class, 'campus']);
    
    Route::post('/confrim-user', action: [AuthController::class, 'confirm']);

    Route::get(uri: '/grade_access', action: [AuthController::class, 'gradeaccess']);

});
