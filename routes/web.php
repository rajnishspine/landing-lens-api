<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PredictController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Predict routes - protected by auth middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/predict', [PredictController::class, 'index'])->name('predict.index');
    Route::post('/predict', [PredictController::class, 'predict'])->name('predict.process');
    Route::get('/predict/history', [PredictController::class, 'history'])->name('predict.history');
    Route::get('/predict/cleanup', [PredictController::class, 'cleanup'])->name('predict.cleanup');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
