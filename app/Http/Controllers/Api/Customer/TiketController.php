<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\JsonResponse;

class TiketController extends Controller
{
    /**
     * Tampilkan detail tiket digital untuk Customer.
     *
     * @param string $booking_code
     * @return JsonResponse
     */
    public function showTicket(string $booking_code): JsonResponse
    {
        $pesanan = Pemesanan::with(['jadwalTrip.paketWisata', 'jadwalTrip.tripLeader'])
            ->where('booking_code', $booking_code)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan.'
            ], 404);
        }

        if ($pesanan->id_customer !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Tiket ini bukan milik Anda.'
            ], 403);
        }

        if ($pesanan->status_pembayaran !== 'PAID') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket belum lunas atau tidak valid.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tiket digital ditemukan.',
            'data' => [
                'booking_code' => $pesanan->booking_code,
                'tgl_pemesanan' => $pesanan->tgl_pemesanan,
                'jumlah_peserta' => $pesanan->jumlah_peserta,
                'jumlah_hadir' => $pesanan->jumlah_hadir,
                'attendance_status' => $pesanan->attendance_status,
                'paket_wisata' => [
                    'nama_paket' => $pesanan->jadwalTrip->paketWisata->nama_paket ?? null,
                    'deskripsi' => $pesanan->jadwalTrip->paketWisata->deskripsi ?? null,
                ],
                'jadwal' => [
                    'tanggal_mulai' => $pesanan->jadwalTrip->tanggal_mulai ?? null,
                    'tanggal_selesai' => $pesanan->jadwalTrip->tanggal_selesai ?? null,
                ],
                'trip_leader' => [
                    'nama_leader' => $pesanan->jadwalTrip->tripLeader->nama_leader ?? null,
                    'no_telp' => $pesanan->jadwalTrip->tripLeader->no_telp ?? null,
                ]
            ]
        ]);
    }
}
