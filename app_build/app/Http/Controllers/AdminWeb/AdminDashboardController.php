<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $revenue = Pemesanan::where('status_pembayaran', 'PAID')->sum(\DB::raw('total_harga + total_biaya_addons'));
        $pax = Pemesanan::where('status_pembayaran', 'PAID')->sum('jumlah_peserta');
        $recent_bookings = Pemesanan::with(['customer', 'jadwalTrip.paketWisata'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('revenue', 'pax', 'recent_bookings'));
    }
}
