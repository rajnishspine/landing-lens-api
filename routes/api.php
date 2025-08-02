<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PredictApiController;
use App\Http\Controllers\Api\AuthController;

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

// Public API Routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    
    // Image Analysis API (Public endpoints - read only)
    Route::get('/predict/{id}', [PredictApiController::class, 'getAnalysis'])->name('api.predict.show');
    Route::get('/predict', [PredictApiController::class, 'index'])->name('api.predict.index');
});

// Authenticated API Routes (require API token)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Authentication Management
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('api.logout.all');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('api.refresh.token');
    
    // Image Analysis API (Authenticated endpoints)
    Route::post('/predict', [PredictApiController::class, 'predict'])->name('api.predict.create');
    
    // User's analysis history and management
    Route::get('/my-analyses', [PredictApiController::class, 'userAnalyses'])->name('api.my.analyses');
    Route::delete('/predict/{id}', [PredictApiController::class, 'deleteAnalysis'])->name('api.predict.delete');
});
