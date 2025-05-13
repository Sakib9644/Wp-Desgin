<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

// Public route (no auth needed)
Route::post('/login', [AuthController::class, 'login']);

// Grouped routes (JWT protected)
Route::middleware('jwt.verify')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
