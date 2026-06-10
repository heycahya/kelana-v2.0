<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\PaketManagementController;
use App\Http\Controllers\Api\Admin\JadwalTripController;
use App\Http\Controllers\Api\Admin\LaporanController;
use App\Http\Controllers\Api\Customer\PemesananController;
use App\Http\Controllers\Api\Customer\TiketController;
use App\Http\Controllers\Api\Customer\UlasanController;
use App\Http\Controllers\Api\Customer\ProfileController;
use App\Http\Controllers\Api\Customer\PesananHistoryController;
use App\Http\Controllers\Api\TripLeader\ManifestController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\Publik\KatalogController;
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
    
    // Webhook Midtrans (Public/No Auth Middleware)
    Route::post('/webhook/midtrans', [WebhookController::class, 'handleMidtrans']);

    // Public Catalog & Search
    Route::get('/publik/paket-wisata', [KatalogController::class, 'index']);
    Route::get('/publik/paket-wisata/{id}', [KatalogController::class, 'show']);

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

        // Laporan PDF Rekap Peserta
        Route::get('/laporan/rekap-peserta/{id_jadwal}', [LaporanController::class, 'downloadRekapPeserta']);
    });

    // Customer Routes
    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        Route::post('/pemesanan', [PemesananController::class, 'store']);
        Route::get('/customer/tiket/{booking_code}', [TiketController::class, 'showTicket']);
        Route::post('/customer/ulasan', [UlasanController::class, 'store']);
        Route::get('/customer/profile', [ProfileController::class, 'show']);
        Route::put('/customer/profile', [ProfileController::class, 'update']);
        Route::get('/customer/pesanan-history', [PesananHistoryController::class, 'index']);
    });

    // Trip Leader Routes
    Route::middleware(['auth:sanctum', 'trip_leader'])->group(function () {
        Route::get('/trip-leader/manifest/{id_jadwal}', [ManifestController::class, 'getManifest']);
        Route::post('/trip-leader/check-in', [ManifestController::class, 'processCheckIn']);
    });
});
