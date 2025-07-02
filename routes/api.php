<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::prefix('auth')->group(function () {
    // Authentication routes
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
});

// Public data routes
Route::get('/companies', [App\Http\Controllers\Api\CompanyController::class, 'index']);

// Protected API routes
Route::middleware(['auth:sanctum', 'active'])->group(function () {
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me']);
        Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::post('/logout-all', [App\Http\Controllers\Api\AuthController::class, 'logoutAll']);
    });

    // Profile routes
    Route::put('/profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/change-password', [App\Http\Controllers\Api\ProfileController::class, 'changePassword']);
    
    // PKL routes
    Route::apiResource('pkl', App\Http\Controllers\Api\PKLController::class);
    Route::apiResource('reports', App\Http\Controllers\Api\ReportController::class);
    Route::apiResource('evaluations', App\Http\Controllers\Api\EvaluationController::class);
    Route::apiResource('messages', App\Http\Controllers\Api\MessageController::class);
    
    // Admin routes
    Route::middleware(['role:admin,koordinator'])->group(function () {
        Route::apiResource('users', App\Http\Controllers\Api\UserController::class);
        Route::apiResource('companies', App\Http\Controllers\Api\CompanyController::class)->except(['index']);
    });
});
