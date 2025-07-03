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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes untuk AJAX calls
Route::middleware(['auth'])->group(function () {
    
    // Competition API
    Route::get('/competitions', [App\Http\Controllers\Api\CompetitionController::class, 'index']);
    Route::get('/competitions/{competition}', [App\Http\Controllers\Api\CompetitionController::class, 'show']);
    
    // Registration API
    Route::get('/registrations', [App\Http\Controllers\Api\RegistrationController::class, 'index']);
    Route::get('/registrations/datatable', [App\Http\Controllers\Api\RegistrationController::class, 'datatable']);
    
    // Payment API
    Route::get('/payments', [App\Http\Controllers\Api\PaymentController::class, 'index']);
    Route::get('/payments/datatable', [App\Http\Controllers\Api\PaymentController::class, 'datatable']);
    
    // User API (Admin only)
    Route::middleware(['role:Super Admin|Admin'])->group(function () {
        Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index']);
        Route::get('/users/datatable', [App\Http\Controllers\Api\UserController::class, 'datatable']);
    });
    
    // Statistics API
    Route::get('/statistics/dashboard', [App\Http\Controllers\Api\StatisticsController::class, 'dashboard']);
    Route::get('/statistics/competition/{competition}', [App\Http\Controllers\Api\StatisticsController::class, 'competition']);
});
