<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanWebController extends Controller
{
    public function index()
    {
        $jadwals = JadwalTrip::with(['paketWisata', 'tripLeader'])
            ->withCount(['pemesanan as paid_bookings_count' => function ($query) {
                $query->where('status_pembayaran', 'PAID');
            }])
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(10);

        return view('admin.laporan.index', compact('jadwals'));
    }

    public function downloadRekapPeserta($id_jadwal)
    {
        $jadwal = JadwalTrip::with(['paketWisata', 'tripLeader'])->findOrFail($id_jadwal);

        $pemesananPaid = $jadwal->pemesanan()
            ->where('status_pembayaran', 'PAID')
            ->with('customer')
            ->get();

        $totalPendapatan = $pemesananPaid->sum('total_harga') + $pemesananPaid->sum('total_biaya_addons');
        $totalPeserta = $pemesananPaid->sum('jumlah_peserta');

        $pdf = Pdf::loadView('pdf.rekap-peserta', [
            'jadwal' => $jadwal,
            'pemesanan' => $pemesananPaid,
            'totalPendapatan' => $totalPendapatan,
            'totalPeserta' => $totalPeserta
        ]);

        return $pdf->download("rekap-peserta-trip-{$id_jadwal}.pdf");
    }
}
