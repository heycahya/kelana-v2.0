<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingWebController extends Controller
{
    /**
     * Show the booking form.
     */
    public function create(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        $jadwal = JadwalTrip::with('paketWisata')->findOrFail($jadwalId);

        return view('customer.booking', compact('jadwal'));
    }

    /**
     * Store a new booking and generate Midtrans Snap Token.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jadwal' => 'required|exists:jadwal_trip,id_jadwal',
            'jumlah_peserta' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $jadwal = JadwalTrip::lockForUpdate()->find($request->id_jadwal);

            if (!$jadwal) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jadwal trip tidak ditemukan.'
                ], 404);
            }

            if ($jadwal->sisa_kuota < $request->jumlah_peserta) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kuota tidak mencukupi'
                ], 422);
            }

            $paket = $jadwal->paketWisata;
            $total_harga = $request->jumlah_peserta * $paket->harga;

            // Generate unique booking code
            $today = date('Ymd');
            do {
                $random_suffix = sprintf('%04d', mt_rand(1, 9999));
                $booking_code = "TRIP-{$today}-{$random_suffix}";
            } while (Pemesanan::where('booking_code', $booking_code)->exists());

            // Create booking record
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

            // Create payment record
            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'jumlah_bayar' => $total_harga,
            ]);

            // Configure Midtrans
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

            // Generate snap_token
            $snapToken = '';
            try {
                $serverKey = env('MIDTRANS_SERVER_KEY');
                if (!empty($serverKey) && $serverKey !== 'placeholder') {
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                } else {
                    $snapToken = 'snap-token-mock-' . Str::random(24);
                }
            } catch (\Exception $e) {
                if (app()->environment('local', 'testing')) {
                    $snapToken = 'snap-token-mock-' . Str::random(24);
                } else {
                    throw $e;
                }
            }

            // Update payment record
            $pembayaran->update([
                'snap_token' => $snapToken
            ]);

            // Deduct quota
            $jadwal->update([
                'sisa_kuota' => $jadwal->sisa_kuota - $request->jumlah_peserta
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pemesanan berhasil dibuat.',
                'snap_token' => $snapToken,
                'booking_code' => $booking_code
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
