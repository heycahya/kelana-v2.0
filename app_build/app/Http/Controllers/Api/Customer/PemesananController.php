<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PemesananController extends Controller
{
    /**
     * Submit form booking & integrasi Midtrans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'id_jadwal' => 'required|exists:jadwal_trip,id_jadwal',
            'jumlah_peserta' => 'required|integer|min:1',
        ], [
            'id_jadwal.required' => 'ID jadwal trip wajib diisi.',
            'id_jadwal.exists' => 'Jadwal trip yang dipilih tidak valid atau tidak ditemukan.',
            'jumlah_peserta.required' => 'Jumlah peserta wajib diisi.',
            'jumlah_peserta.integer' => 'Jumlah peserta harus berupa angka.',
            'jumlah_peserta.min' => 'Jumlah peserta minimal 1 orang.',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[$field] = $messages[0];
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $errors
            ], 422);
        }

        // 2. DB Transaction
        DB::beginTransaction();

        try {
            // Cek tabel jadwal_trip dengan lock untuk menghindari race condition
            $jadwal = JadwalTrip::lockForUpdate()->find($request->id_jadwal);

            if (!$jadwal) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jadwal trip tidak ditemukan.'
                ], 404);
            }

            // Pastikan sisa_kuota >= jumlah_peserta
            if ($jadwal->sisa_kuota < $request->jumlah_peserta) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kuota tidak mencukupi'
                ], 422);
            }

            // Ambil harga dari paket wisata relasi
            $paket = $jadwal->paketWisata;
            if (!$paket) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Paket wisata tidak ditemukan untuk jadwal ini.'
                ], 404);
            }

            // Kalkulasi: total_harga = jumlah_peserta * harga paket_wisata
            $total_harga = $request->jumlah_peserta * $paket->harga;

            // Generate booking_code unik (contoh: TRIP-YYYYMMDD-XXXX)
            $today = date('Ymd');
            do {
                $random_suffix = sprintf('%04d', mt_rand(1, 9999));
                $booking_code = "TRIP-{$today}-{$random_suffix}";
            } while (Pemesanan::where('booking_code', $booking_code)->exists());

            // Insert ke tabel 'pemesanan' (status_pembayaran = 'PENDING')
            $pemesanan = Pemesanan::create([
                'booking_code' => $booking_code,
                'id_customer' => auth()->id(),
                'id_jadwal' => $request->id_jadwal,
                'tgl_pemesanan' => now(),
                'jumlah_peserta' => $request->jumlah_peserta,
                'total_harga' => $total_harga,
                'status_pembayaran' => 'PENDING',
                'attendance_status' => 'belum_hadir',
            ]);

            // Insert ke tabel 'pembayaran' (id_pemesanan, jumlah_bayar = total_harga)
            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'jumlah_bayar' => $total_harga,
            ]);

            // Integrasi Midtrans: Konfigurasi Sandbox
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $customer = auth()->user();

            $params = [
                'transaction_details' => [
                    'order_id' => $booking_code,
                    'gross_amount' => (int) $total_harga,
                ],
                'customer_details' => [
                    'first_name' => $customer->nama_customer,
                    'email' => $customer->email,
                    'phone' => $customer->no_telp,
                ],
            ];

            // Generate snap_token menggunakan \Midtrans\Snap::getSnapToken($params)
            $snapToken = '';
            try {
                // Hanya memanggil Midtrans API asli jika server key terkonfigurasi dengan benar
                $serverKey = env('MIDTRANS_SERVER_KEY');
                if (!empty($serverKey) && $serverKey !== 'placeholder') {
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                } else {
                    // Fallback untuk local sandbox testing tanpa credential valid
                    $snapToken = 'snap-token-mock-' . Str::random(24);
                }
            } catch (\Exception $e) {
                // Fallback jika terjadi error koneksi atau response error dari Midtrans di mode local
                if (app()->environment('local', 'testing')) {
                    $snapToken = 'snap-token-mock-' . Str::random(24);
                } else {
                    throw $e;
                }
            }

            // Update tabel 'pembayaran': Simpan snap_token yang didapat
            $pembayaran->update([
                'snap_token' => $snapToken
            ]);

            // Kurangi sisa_kuota di tabel jadwal_trip
            $jadwal->update([
                'sisa_kuota' => $jadwal->sisa_kuota - $request->jumlah_peserta
            ]);

            DB::commit();

            // Format Response Sukses (201)
            return response()->json([
                'status' => 'success',
                'message' => 'Pemesanan berhasil dibuat.',
                'data' => [
                    'booking_code' => $booking_code,
                    'total_harga' => $total_harga,
                    'snap_token' => $snapToken,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses pemesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
