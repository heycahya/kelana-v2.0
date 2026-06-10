<?php

namespace App\Http\Controllers\Api\TripLeader;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use App\Models\Pemesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManifestController extends Controller
{
    /**
     * Tampilkan daftar manifes peserta yang lunas (PAID) pada jadwal trip tertentu.
     *
     * @param int $id_jadwal
     * @return JsonResponse
     */
    public function getManifest(int $id_jadwal): JsonResponse
    {
        // 1. Cek apakah jadwal trip tersedia
        $jadwal = JadwalTrip::with(['paketWisata', 'tripLeader'])->find($id_jadwal);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal trip tidak ditemukan.'
            ], 404);
        }

        // 2. Proteksi kepemilikan jadwal trip (hanya leader yang ditugaskan)
        if ($jadwal->id_leader !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Jadwal trip ini ditugaskan ke Trip Leader lain.'
            ], 403);
        }

        // 3. Ambil data pemesanan status PAID
        $manifes = Pemesanan::with(['customer'])
            ->where('id_jadwal', $id_jadwal)
            ->where('status_pembayaran', 'PAID')
            ->get()
            ->map(function ($item) {
                return [
                    'booking_code' => $item->booking_code,
                    'nama_customer' => $item->customer->nama_customer ?? null,
                    'jumlah_peserta' => $item->jumlah_peserta,
                    'jumlah_hadir' => $item->jumlah_hadir,
                    'attendance_status' => $item->attendance_status,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Manifes peserta berhasil dimuat.',
            'data' => [
                'jadwal' => [
                    'id_jadwal' => $jadwal->id_jadwal,
                    'paket_wisata' => $jadwal->paketWisata->nama_paket ?? null,
                    'tanggal_mulai' => $jadwal->tanggal_mulai,
                    'tanggal_selesai' => $jadwal->tanggal_selesai,
                ],
                'manifes' => $manifes
            ]
        ]);
    }

    /**
     * Memproses check-in kehadiran peserta secara kuantitatif.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processCheckIn(Request $request): JsonResponse
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'booking_code' => 'required|string',
            'jumlah_check_in' => 'required|integer|min:1'
        ], [
            'booking_code.required' => 'Kode booking wajib diisi.',
            'booking_code.string' => 'Kode booking harus berupa teks.',
            'jumlah_check_in.required' => 'Jumlah check-in wajib diisi.',
            'jumlah_check_in.integer' => 'Jumlah check-in harus berupa angka.',
            'jumlah_check_in.min' => 'Jumlah check-in minimal 1 orang.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Memulai DB Transaction
        DB::beginTransaction();

        try {
            // 3. Ambil data pesanan dengan row locking (lockForUpdate)
            $pesanan = Pemesanan::where('booking_code', $request->booking_code)
                ->lockForUpdate()
                ->first();

            // 4. Jika pesanan tidak ditemukan
            if (!$pesanan) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan.'
                ], 404);
            }

            // 5. Cek kepemilikan jadwal trip oleh Trip Leader yang sedang login
            $jadwal = JadwalTrip::find($pesanan->id_jadwal);
            if (!$jadwal || $jadwal->id_leader !== auth()->id()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda bukan Trip Leader untuk jadwal trip ini.'
                ], 403);
            }

            // 6. Jika pesanan belum dibayar/lunas
            if ($pesanan->status_pembayaran !== 'PAID') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan belum lunas.'
                ], 400);
            }

            // 7. Validasi kuota check-in
            $akumulasi_hadir_baru = $pesanan->jumlah_hadir + $request->jumlah_check_in;

            if ($akumulasi_hadir_baru > $pesanan->jumlah_peserta) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah check-in melebihi total kuota tiket.',
                    'sisa_kuota' => $pesanan->jumlah_peserta - $pesanan->jumlah_hadir
                ], 422);
            }

            // 8. Update data
            $pesanan->jumlah_hadir = $akumulasi_hadir_baru;
            // Jika semua peserta sudah check-in, ubah status kehadiran menjadi 'hadir'
            $pesanan->attendance_status = ($akumulasi_hadir_baru === $pesanan->jumlah_peserta) ? 'hadir' : 'belum_hadir';
            $pesanan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil diproses.',
                'data' => [
                    'booking_code' => $pesanan->booking_code,
                    'jumlah_hadir' => $pesanan->jumlah_hadir,
                    'total_peserta' => $pesanan->jumlah_peserta,
                    'attendance_status' => $pesanan->attendance_status
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat check-in: ' . $e->getMessage()
            ], 500);
        }
    }
}
