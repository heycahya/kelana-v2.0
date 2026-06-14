<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\JsonResponse;

class PesananHistoryController extends Controller
{
    /**
     * Tampilkan riwayat pemesanan/trip untuk Customer.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $customerId = auth()->id();
        $today = now()->toDateString();

        // 1. Ambil Active Trips
        // Kriteria: status_pembayaran 'PAID', tanggal_mulai >= hari ini, dan status_trip bukan 'Selesai'
        $activeTrips = Pemesanan::with(['jadwalTrip.paketWisata'])
            ->where('id_customer', $customerId)
            ->where('status_pembayaran', 'PAID')
            ->whereHas('jadwalTrip', function ($query) use ($today) {
                $query->where('tanggal_mulai', '>=', $today)
                      ->where('status_trip', '!=', 'Selesai');
            })
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id_pemesanan,
                    'booking_code' => $booking->booking_code,
                    'trip_name' => $booking->jadwalTrip->paketWisata->nama_paket ?? null,
                    'tanggal_mulai' => $booking->jadwalTrip->tanggal_mulai ?? null,
                    'status_pembayaran' => $booking->status_pembayaran,
                    'kuota_rombongan' => $booking->jumlah_peserta,
                    'jumlah_hadir' => $booking->jumlah_hadir,
                    'status_perjalanan' => $booking->jadwalTrip->status_trip ?? null,
                ];
            });

        // 2. Ambil Past Trips
        // Kriteria: status_pembayaran 'PAID', tanggal_mulai < hari ini ATAU status_trip sudah 'Selesai'
        $pastTrips = Pemesanan::with(['jadwalTrip.paketWisata'])
            ->where('id_customer', $customerId)
            ->where('status_pembayaran', 'PAID')
            ->whereHas('jadwalTrip', function ($query) use ($today) {
                $query->where(function ($q) use ($today) {
                    $q->where('tanggal_mulai', '<', $today)
                      ->orWhere('status_trip', 'Selesai');
                });
            })
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id_pemesanan,
                    'booking_code' => $booking->booking_code,
                    'trip_name' => $booking->jadwalTrip->paketWisata->nama_paket ?? null,
                    'tanggal_mulai' => $booking->jadwalTrip->tanggal_mulai ?? null,
                    'status_pembayaran' => $booking->status_pembayaran,
                    'kuota_rombongan' => $booking->jumlah_peserta,
                    'jumlah_hadir' => $booking->jumlah_hadir,
                    'status_perjalanan' => $booking->jadwalTrip->status_trip ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'active_trips' => $activeTrips,
                'past_trips' => $pastTrips,
            ]
        ], 200);
    }
}
