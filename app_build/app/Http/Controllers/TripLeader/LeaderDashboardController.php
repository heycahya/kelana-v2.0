<?php

namespace App\Http\Controllers\TripLeader;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use Illuminate\Http\Request;

class LeaderDashboardController extends Controller
{
    public function index()
    {
        $leaderId = auth()->id();
        
        $jadwals = JadwalTrip::with(['paketWisata', 'pemesanan'])
            ->where('id_leader', $leaderId)
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Calculate real-time stats
        $totalTrips = $jadwals->count();

        $paidBookings = \App\Models\Pemesanan::whereHas('jadwalTrip', function ($q) use ($leaderId) {
            $q->where('id_leader', $leaderId);
        })->where('status_pembayaran', 'PAID')->get();

        $totalPax = $paidBookings->sum('jumlah_peserta');
        $attendedPax = $paidBookings->where('attendance_status', 'hadir')->sum('jumlah_peserta');
        $checkInRate = $totalPax > 0 ? round(($attendedPax / $totalPax) * 100) : 100;

        return view('leader.dashboard', compact('jadwals', 'totalTrips', 'totalPax', 'checkInRate'));
    }

    public function statistics()
    {
        $leaderId = auth()->id();
        
        $jadwals = JadwalTrip::with(['paketWisata', 'pemesanan'])
            ->where('id_leader', $leaderId)
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Calculate real-time stats
        $totalTrips = $jadwals->count();
        $draftTrips = $jadwals->where('status_trip', 'Draft')->count();
        $openTrips = $jadwals->where('status_trip', 'Open')->count();
        $runningTrips = $jadwals->where('status_trip', 'Berjalan')->count();
        $completedTrips = $jadwals->where('status_trip', 'Selesai')->count();

        $paidBookings = \App\Models\Pemesanan::whereHas('jadwalTrip', function ($q) use ($leaderId) {
            $q->where('id_leader', $leaderId);
        })->where('status_pembayaran', 'PAID')->get();

        $totalPax = $paidBookings->sum('jumlah_peserta');
        $attendedPax = $paidBookings->where('attendance_status', 'hadir')->sum('jumlah_peserta');
        $checkInRate = $totalPax > 0 ? round(($attendedPax / $totalPax) * 100) : 100;

        return view('leader.statistics', compact(
            'jadwals',
            'totalTrips',
            'draftTrips',
            'openTrips',
            'runningTrips',
            'completedTrips',
            'totalPax',
            'attendedPax',
            'checkInRate'
        ));
    }
}

