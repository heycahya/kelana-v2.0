<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::guard('trip_leader')->check()) {
        return redirect()->route('trip_leader.dashboard');
    }
    return view('dashboard', ['role' => 'Customer']);
})->middleware(['auth:customer,admin,trip_leader'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return view('dashboard', ['role' => 'Admin']);
})->middleware(['auth:admin'])->name('admin.dashboard');

Route::get('/trip-leader/dashboard', function () {
    return view('dashboard', ['role' => 'Trip Leader']);
})->middleware(['auth:trip_leader'])->name('trip_leader.dashboard');

Route::middleware('auth:customer,admin,trip_leader')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
