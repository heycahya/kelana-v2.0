<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\PaketManagementController;
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

    // Admin Paket Wisata CRUD
    Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/paket-wisata', [PaketManagementController::class, 'index']);
        Route::post('/paket-wisata', [PaketManagementController::class, 'store']);
        Route::get('/paket-wisata/{id}', [PaketManagementController::class, 'show']);
        Route::put('/paket-wisata/{id}', [PaketManagementController::class, 'update']);
        Route::delete('/paket-wisata/{id}', [PaketManagementController::class, 'destroy']);
    });
});
