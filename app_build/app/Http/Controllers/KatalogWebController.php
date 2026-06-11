<?php

namespace App\Http\Controllers;

use App\Models\PaketWisata;
use App\Models\JadwalTrip;
use Illuminate\Http\Request;

class KatalogWebController extends Controller
{
    /**
     * Display landing page with packages catalog.
     */
    public function index()
    {
        $paketWisata = PaketWisata::all();
        return view('welcome', compact('paketWisata'));
    }

    /**
     * Display package details with active schedules.
     */
    public function show($id)
    {
        $paket = PaketWisata::findOrFail($id);
        
        // Fetch only active schedules (e.g. status_trip is Open/Berjalan and date is today or future)
        $today = now()->toDateString();
        $activeSchedules = JadwalTrip::where('id_paket', $id)
            ->whereIn('status_trip', ['Open', 'Berjalan'])
            ->where('tanggal_mulai', '>=', $today)
            ->get();

        return view('publik.detail', compact('paket', 'activeSchedules'));
    }
}
