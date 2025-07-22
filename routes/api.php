<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TwebPendudukController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected routes (require authentication via Sanctum)
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Get the authenticated user's info
    Route::get('/user', function(Request $request) {
        return $request->user();
    });

    Route::get('/penduduk/clusters/detailed', [TwebPendudukController::class, 'getClustersDetailed']);
    Route::get('/penduduk/all', [TwebPendudukController::class, 'getAllPendudukFromAllDesa']);

    // Example: Get penduduk data for a specific desa
    Route::get('/penduduk/{desa}', [TwebPendudukController::class, 'index']);
    // Store new penduduk data for a specific desa
    Route::post('/penduduk/{desa}', [TwebPendudukController::class, 'store']);
    // Show specific penduduk by id for a specific desa
    Route::get('/penduduk/{desa}/{id}', [TwebPendudukController::class, 'show']);
    // Update specific penduduk by id for a specific desa
    Route::put('/penduduk/{desa}/{id}', [TwebPendudukController::class, 'update']);
    Route::patch('/penduduk/{desa}/{id}', [TwebPendudukController::class, 'update']);
    // Delete specific penduduk by id for a specific desa
    Route::delete('/penduduk/{desa}/{id}', [TwebPendudukController::class, 'destroy']);
    // Get penduduk by NIK for a specific desa
    Route::get('/penduduk/{desa}/nik/{nik}', [TwebPendudukController::class, 'getByNik']);
    // Get detailed clusters

});
