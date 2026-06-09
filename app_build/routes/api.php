<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\PaketManagementController;
use App\Http\Controllers\Api\Admin\JadwalTripController;
use App\Http\Controllers\Api\Customer\PemesananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider or through application
| bootstrap configuration, and all of them will be assigned to the "api"
| middleware group.
|
*/

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Admin CRUD Routes
    Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
        // Paket Wisata
        Route::get('/paket-wisata', [PaketManagementController::class, 'index']);
        Route::post('/paket-wisata', [PaketManagementController::class, 'store']);
        Route::get('/paket-wisata/{id}', [PaketManagementController::class, 'show']);
        Route::put('/paket-wisata/{id}', [PaketManagementController::class, 'update']);
        Route::delete('/paket-wisata/{id}', [PaketManagementController::class, 'destroy']);

        // Jadwal Trip
        Route::get('/jadwal-trip', [JadwalTripController::class, 'index']);
        Route::post('/jadwal-trip', [JadwalTripController::class, 'store']);
        Route::get('/jadwal-trip/{id}', [JadwalTripController::class, 'show']);
        Route::put('/jadwal-trip/{id}', [JadwalTripController::class, 'update']);
        Route::delete('/jadwal-trip/{id}', [JadwalTripController::class, 'destroy']);
    });

    // Customer Routes
    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        Route::post('/pemesanan', [PemesananController::class, 'store']);
    });
});
