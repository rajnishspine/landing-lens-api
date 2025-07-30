<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PredictApiController;

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
    // Image Analysis API
    Route::post('/predict', [PredictApiController::class, 'predict'])->name('api.predict.create');
    Route::get('/predict/{id}', [PredictApiController::class, 'getAnalysis'])->name('api.predict.show');
    Route::get('/predict', [PredictApiController::class, 'index'])->name('api.predict.index');
});

// Authenticated API Routes (require API token)
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // User's analysis history
    Route::prefix('v1')->group(function () {
        Route::get('/my-analyses', [PredictApiController::class, 'userAnalyses']);
        Route::delete('/predict/{id}', [PredictApiController::class, 'deleteAnalysis']);
    });
});
