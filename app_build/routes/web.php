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
        return redirect()->route('leader.dashboard');
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

    $query = \App\Models\PaketWisata::query();
    if (request()->has('search') && !empty(request()->search)) {
        $search = request()->search;
        $query->where(function($q) use ($search) {
            $q->where('nama_paket', 'like', "%{$search}%")
              ->orWhere('rute', 'like', "%{$search}%");
        });
    }
    $paketWisata = $query->get();

    return view('dashboard', [
        'role' => 'Customer',
        'activeTrips' => $activeTrips,
        'pastTrips' => $pastTrips,
        'paketWisata' => $paketWisata
    ]);
})->middleware(['auth:customer,admin,trip_leader'])->name('dashboard');

// --- ADMIN BACK-OFFICE ROUTES ---
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminWeb\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('paket', \App\Http\Controllers\AdminWeb\PaketWebController::class);
    Route::resource('jadwal', \App\Http\Controllers\AdminWeb\JadwalWebController::class);
    Route::get('/laporan', [\App\Http\Controllers\AdminWeb\LaporanWebController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/download/{id_jadwal}', [\App\Http\Controllers\AdminWeb\LaporanWebController::class, 'downloadRekapPeserta'])->name('laporan.download');
    
    // DB-integrated functional modules
    Route::get('/customers', [\App\Http\Controllers\AdminWeb\CustomerWebController::class, 'index'])->name('customers.index');
    Route::get('/transaksi', [\App\Http\Controllers\AdminWeb\TransaksiWebController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{id}/update-status', [\App\Http\Controllers\AdminWeb\TransaksiWebController::class, 'updateStatus'])->name('transaksi.updateStatus');

    // Chat CS Inbox Module
    Route::get('/messages', [\App\Http\Controllers\AdminWeb\ChatAdminWebController::class, 'index'])->name('messages.index');
    Route::get('/messages/contacts', [\App\Http\Controllers\AdminWeb\ChatAdminWebController::class, 'getContacts'])->name('messages.contacts');
    Route::get('/messages/{contact_type}/{contact_id}', [\App\Http\Controllers\AdminWeb\ChatAdminWebController::class, 'getMessages'])->name('messages.thread');
    Route::post('/messages/{contact_type}/{contact_id}', [\App\Http\Controllers\AdminWeb\ChatAdminWebController::class, 'sendMessage'])->name('messages.send');
});

// --- TRIP LEADER FIELD APP ROUTES ---
Route::middleware(['auth:trip_leader', 'trip_leader'])->prefix('trip-leader')->name('leader.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TripLeader\LeaderDashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [\App\Http\Controllers\TripLeader\LeaderDashboardController::class, 'statistics'])->name('statistics');
    Route::get('/manifest/{id_jadwal}', [\App\Http\Controllers\TripLeader\ManifestWebController::class, 'show'])->name('manifest.show');
    Route::post('/manifest/check-in', [\App\Http\Controllers\TripLeader\ManifestWebController::class, 'checkIn'])->name('manifest.checkIn');
    
    // Chat with Admin Support
    Route::get('/chat', [\App\Http\Controllers\TripLeader\LeaderChatWebController::class, 'index'])->name('chat.index');
    Route::get('/chat/messages', [\App\Http\Controllers\TripLeader\LeaderChatWebController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/messages', [\App\Http\Controllers\TripLeader\LeaderChatWebController::class, 'sendMessage'])->name('chat.send');
});

Route::middleware('auth:customer,admin,trip_leader')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\Customer\BookingWebController;
use App\Http\Controllers\Customer\WishlistWebController;
use App\Http\Controllers\Customer\CartWebController;

Route::middleware('auth:customer')->group(function () {
    Route::get('/booking', [BookingWebController::class, 'create'])->name('customer.booking');
    Route::post('/booking', [BookingWebController::class, 'store'])->name('customer.booking.store');
    Route::get('/booking/tiket/{booking_code}/pdf', [BookingWebController::class, 'downloadTicketPdf'])->name('customer.booking.ticket.pdf');
    Route::get('/my-bookings', [BookingWebController::class, 'myBookings'])->name('customer.bookings');
    
    Route::get('/wishlist', [WishlistWebController::class, 'index'])->name('customer.wishlist.index');
    Route::post('/wishlist', [WishlistWebController::class, 'store'])->name('customer.wishlist.store');
    Route::delete('/wishlist/{id}', [WishlistWebController::class, 'destroy'])->name('customer.wishlist.destroy');

    Route::get('/cart', [CartWebController::class, 'index'])->name('customer.cart.index');
    Route::delete('/cart/{id}', [CartWebController::class, 'destroy'])->name('customer.cart.destroy');

    // Customer Support Chat
    Route::get('/chat/messages', [\App\Http\Controllers\Customer\CustomerChatWebController::class, 'getMessages'])->name('customer.chat.messages');
    Route::post('/chat/messages', [\App\Http\Controllers\Customer\CustomerChatWebController::class, 'sendMessage'])->name('customer.chat.send');
});

require __DIR__.'/auth.php';
