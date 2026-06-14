<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

class LaporanController extends Controller
{
    /**
     * Download PDF manifest rekap peserta per jadwal trip.
     *
     * @param int $id_jadwal
     * @return mixed
     */
    public function downloadRekapPeserta($id_jadwal)
    {
        // 1. Ambil data JadwalTrip beserta relasi PaketWisata dan TripLeader
        $jadwal = JadwalTrip::with(['paketWisata', 'tripLeader'])->find($id_jadwal);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Data jadwal trip tidak ditemukan',
                'data' => null
            ], 404);
        }

        // 2. Ambil data Pemesanan yang lunas (PAID) untuk jadwal ini beserta data Customer
        $pemesananPaid = $jadwal->pemesanan()
            ->where('status_pembayaran', 'PAID')
            ->with('customer')
            ->get();

        // 3. Hitung statistik total pendapatan
        $totalPendapatan = $pemesananPaid->sum('total_harga');

        // 4. Hitung total peserta yang lunas
        $totalPeserta = $pemesananPaid->sum('jumlah_peserta');

        // 5. Load view PDF
        $pdf = Pdf::loadView('pdf.rekap-peserta', [
            'jadwal' => $jadwal,
            'pemesanan' => $pemesananPaid,
            'totalPendapatan' => $totalPendapatan,
            'totalPeserta' => $totalPeserta
        ]);

        // 6. Download PDF file
        return $pdf->download("rekap-peserta-trip-{$id_jadwal}.pdf");
    }
}
