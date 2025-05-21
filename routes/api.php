<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

// Public route (no auth needed)
Route::post('/login', [AuthController::class, 'login']);



  Route::post('/reset-password', [AuthController::class, 'reset_password']);  
  Route::post('/password-update', [AuthController::class, 'update_password']);


// Grouped routes (JWT protected)
Route::middleware('jwt.verify')->group(function () {

});
