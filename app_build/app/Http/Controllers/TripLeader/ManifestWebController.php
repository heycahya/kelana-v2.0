<?php

namespace App\Http\Controllers\TripLeader;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManifestWebController extends Controller
{
    public function show($id_jadwal)
    {
        $jadwal = JadwalTrip::with(['paketWisata', 'pemesanan.customer'])
            ->where('id_leader', auth()->id())
            ->findOrFail($id_jadwal);

        return view('leader.manifest', compact('jadwal'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'kode_booking' => 'required|string',
            'id_jadwal' => 'nullable|exists:jadwal_trip,id_jadwal'
        ]);

        DB::beginTransaction();

        try {
            // Find booking by booking_code (column name is booking_code, form input is kode_booking)
            $pesanan = Pemesanan::where('booking_code', $request->kode_booking)
                ->lockForUpdate()
                ->first();

            // Verification: Existence
            if (!$pesanan) {
                DB::rollBack();
                return back()->with('error', '❌ Tiket tidak ditemukan di sistem!');
            }

            // Verification: Assigned leader authorization
            $jadwal = JadwalTrip::find($pesanan->id_jadwal);
            if (!$jadwal || $jadwal->id_leader !== auth()->id()) {
                DB::rollBack();
                return back()->with('error', '❌ Anda tidak ditugaskan untuk memimpin trip ini!');
            }

            // Verification: Schedule mismatch if passed
            if ($request->has('id_jadwal') && $pesanan->id_jadwal != $request->id_jadwal) {
                DB::rollBack();
                return back()->with('error', '❌ Tiket ini bukan untuk jadwal trip ini!');
            }

            // Verification: Payment status
            if ($pesanan->status_pembayaran !== 'PAID') {
                DB::rollBack();
                return back()->with('error', '❌ Tiket belum lunas!');
            }

            // Verification: Already checked-in
            if ($pesanan->attendance_status === 'hadir') {
                DB::rollBack();
                return back()->with('error', '⚠️ Peserta ini SUDAH check-in sebelumnya!');
            }

            // Success: Update attendance
            $pesanan->attendance_status = 'hadir';
            $pesanan->jumlah_hadir = $pesanan->jumlah_peserta;
            $pesanan->save();

            DB::commit();

            $customerName = $pesanan->customer->nama_customer ?? 'Peserta';
            return back()->with('success', '✅ ' . $customerName . ' Berhasil Check-In!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
