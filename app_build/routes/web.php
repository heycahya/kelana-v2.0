<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\KatalogWebController;

Route::get('/', [KatalogWebController::class, 'index'])->name('home');
Route::get('/paket-wisata/{id}', [KatalogWebController::class, 'show'])->name('paket.detail');

Route::get('/dashboard', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::guard('trip_leader')->check()) {
        return redirect()->route('trip_leader.dashboard');
    }

    $user = Auth::guard('customer')->user();
    
    $pemesanan = [];
    if ($user) {
        $pemesanan = \App\Models\Pemesanan::where('id_customer', $user->id_customer)
            ->with(['jadwal.paketWisata'])
            ->get();
    } else {
        $pemesanan = collect();
    }

    $today = now()->toDateString();

    $activeTrips = $pemesanan->filter(function ($trip) use ($today) {
        $jadwal = $trip->jadwal;
        return $jadwal && $jadwal->tanggal_mulai >= $today && $jadwal->status_trip !== 'Selesai';
    });

    $pastTrips = $pemesanan->filter(function ($trip) use ($today) {
        $jadwal = $trip->jadwal;
        return $jadwal && ($jadwal->tanggal_mulai < $today || $jadwal->status_trip === 'Selesai');
    });

    $paketWisata = \App\Models\PaketWisata::all();

    return view('dashboard', [
        'role' => 'Customer',
        'activeTrips' => $activeTrips,
        'pastTrips' => $pastTrips,
        'paketWisata' => $paketWisata
    ]);
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

use App\Http\Controllers\Customer\BookingWebController;

Route::middleware('auth:customer')->group(function () {
    Route::get('/booking', [BookingWebController::class, 'create'])->name('customer.booking');
    Route::post('/booking', [BookingWebController::class, 'store'])->name('customer.booking.store');
});

require __DIR__.'/auth.php';
