<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\JadwalTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Midtrans Webhook Notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleMidtrans(Request $request)
    {
        // Konfigurasi Midtrans Sandbox
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;

        try {
            $transactionStatus = null;
            $orderId = null;
            $paymentType = null;
            $transactionId = null;
            $transactionTime = null;
            $rawResponse = null;

            $signatureKey = null;
            $statusCode = null;
            $grossAmount = null;

            try {
                // Jika server key kosong atau placeholder, bypass Midtrans library call
                $serverKey = env('MIDTRANS_SERVER_KEY');
                if (empty($serverKey) || $serverKey === 'placeholder') {
                    throw new \Exception("Local mock testing fallback activated (No valid Server Key)");
                }
                
                $notification = new \Midtrans\Notification();
                $transactionStatus = $notification->transaction_status;
                $orderId = $notification->order_id;
                $paymentType = $notification->payment_type;
                $transactionId = $notification->transaction_id;
                $transactionTime = $notification->transaction_time;
                $rawResponse = json_encode($notification->getResponse());
                
                $signatureKey = $notification->signature_key;
                $statusCode = $notification->status_code;
                $grossAmount = $notification->gross_amount;
            } catch (\Exception $e) {
                // Fallback: Ambil data langsung dari request body jika di sandbox / mock
                $transactionStatus = $request->input('transaction_status');
                $orderId = $request->input('order_id');
                $paymentType = $request->input('payment_type');
                $transactionId = $request->input('transaction_id');
                $transactionTime = $request->input('transaction_time', now()->toDateTimeString());
                $rawResponse = json_encode($request->all());
                
                $signatureKey = $request->input('signature_key');
                $statusCode = $request->input('status_code');
                $grossAmount = $request->input('gross_amount');
            }

            // Webhook Signature validation if MIDTRANS_SERVER_KEY is set and valid
            $serverKey = env('MIDTRANS_SERVER_KEY');
            if (!empty($serverKey) && $serverKey !== 'placeholder') {
                if (empty($signatureKey) && app()->environment('local', 'testing')) {
                    Log::info("Webhook Midtrans: Bypassing signature verification for local mock testing (no signature_key provided).");
                } else {
                    $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
                    if ($mySignature !== $signatureKey) {
                        Log::warning("Webhook Midtrans Signature Verification Failed. Expected: {$mySignature}, Received: {$signatureKey}");
                        return response()->json(['message' => 'Invalid signature'], 403);
                    }
                }
            }

            if (empty($orderId)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order ID tidak ditemukan dalam data webhook'
                ], 400);
            }
            
            // PENTING: Gunakan DB Transaction
            return DB::transaction(function () use ($transactionStatus, $orderId, $paymentType, $transactionId, $transactionTime, $rawResponse) {
                // Cari data pemesanan berdasarkan booking_code
                $pemesanan = Pemesanan::where('booking_code', $orderId)->first();
                
                if (!$pemesanan) {
                    Log::warning("Webhook Midtrans: Pemesanan dengan booking_code {$orderId} tidak ditemukan.");
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pemesanan tidak ditemukan'
                    ], 404);
                }

                // Ambil data pembayaran terkait
                $pembayaran = Pembayaran::where('id_pemesanan', $pemesanan->id_pemesanan)->first();
                if (!$pembayaran) {
                    Log::warning("Webhook Midtrans: Pembayaran untuk pemesanan ID {$pemesanan->id_pemesanan} tidak ditemukan.");
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data pembayaran tidak ditemukan'
                    ], 404);
                }

                // Update detail transaksi pembayaran
                $pembayaranUpdate = [
                    'transaction_id' => $transactionId,
                    'metode_pembayaran' => $paymentType,
                    'tgl_pembayaran' => $transactionTime,
                    'bukti_pembayaran' => $rawResponse,
                ];

                // Logika status berdasarkan Midtrans
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    $pemesanan->update(['status_pembayaran' => 'PAID']);
                    $pembayaranUpdate['status_transaksi'] = 'SUCCESS';
                    $pembayaran->update($pembayaranUpdate);

                    // Send automated bot message
                    \App\Models\Message::create([
                        'sender_type' => 'admin',
                        'sender_id' => 1,
                        'receiver_type' => 'customer',
                        'receiver_id' => $pemesanan->id_customer,
                        'message' => "Pembayaran Anda untuk Kode Booking {$pemesanan->booking_code} telah berhasil dikonfirmasi secara otomatis! Status pemesanan Anda kini telah berubah menjadi PAID. Silakan download E-Ticket PDF Anda di menu 'Tiket Saya'. Terima kasih!",
                        'is_read' => false
                    ]);

                    // Trip Leader automatically contacts CS/Admin and Customer
                    $pemesanan->load(['jadwalTrip.paketWisata', 'jadwalTrip.tripLeader', 'customer']);
                    if ($pemesanan->jadwalTrip && $pemesanan->jadwalTrip->id_leader) {
                        $leader = $pemesanan->jadwalTrip->tripLeader;
                        if ($leader) {
                            $paketName = $pemesanan->jadwalTrip->paketWisata->nama_paket ?? 'Trip Wisata';
                            $leaderName = $leader->nama_leader ?? 'Trip Leader';
                            
                            // Send confirmation to Admin (CS)
                            $leaderMessage = "Halo CS/Admin, saya {$leaderName} selaku Trip Leader untuk paket {$paketName} (Booking Code: {$pemesanan->booking_code}). Saya mengonfirmasi bahwa saya siap bertugas memandu trip ini!";
                            \App\Models\Message::create([
                                'sender_type' => 'trip_leader',
                                'sender_id' => $leader->id_leader,
                                'receiver_type' => 'admin',
                                'receiver_id' => 1, // Default Admin ID
                                'message' => $leaderMessage,
                                'is_read' => false
                            ]);

                            // Send confirmation to Customer
                            $custName = $pemesanan->customer->nama_customer ?? 'Kak';
                            $customerMessage = "Halo {$custName}, saya {$leaderName} selaku Trip Leader Anda untuk paket {$paketName}. Pembayaran Anda telah terverifikasi lunas (PAID). Saya siap memandu perjalanan Anda! Silakan balas chat ini langsung jika ada koordinasi teknis atau perlengkapan yang dibutuhkan.";
                            \App\Models\Message::create([
                                'sender_type' => 'trip_leader',
                                'sender_id' => $leader->id_leader,
                                'receiver_type' => 'customer',
                                'receiver_id' => $pemesanan->id_customer,
                                'message' => $customerMessage,
                                'is_read' => false
                            ]);
                        }
                    }
                } 
                elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                    // Update status pemesanan & pembayaran
                    $pemesanan->update(['status_pembayaran' => 'FAILED']);
                    $pembayaranUpdate['status_transaksi'] = strtoupper($transactionStatus); // FAILED/EXPIRED/CANCELLED
                    if ($transactionStatus == 'expire') {
                        $pembayaranUpdate['status_transaksi'] = 'EXPIRED';
                    } elseif ($transactionStatus == 'deny') {
                        $pembayaranUpdate['status_transaksi'] = 'FAILED';
                    } elseif ($transactionStatus == 'cancel') {
                        $pemesanan->update(['status_pembayaran' => 'CANCELLED']);
                    }
                    $pembayaran->update($pembayaranUpdate);

                    // Send automated bot message
                    $statusName = $transactionStatus == 'cancel' ? 'dibatalkan' : 'gagal/kedaluwarsa';
                    \App\Models\Message::create([
                        'sender_type' => 'admin',
                        'sender_id' => 1,
                        'receiver_type' => 'customer',
                        'receiver_id' => $pemesanan->id_customer,
                        'message' => "Pemesanan Anda untuk Kode Booking {$pemesanan->booking_code} telah {$statusName}. Jika Anda telah melakukan pembayaran, silakan hubungi CS kami dengan bukti transaksi untuk klaim pengembalian dana (refund).",
                        'is_read' => false
                    ]);

                    // PENTING: Increment sisa_kuota di tabel jadwal_trip sebesar jumlah_peserta agar kuota tidak hangus
                    $jadwal = JadwalTrip::lockForUpdate()->find($pemesanan->id_jadwal);
                    if ($jadwal) {
                        $jadwal->update([
                            'sisa_kuota' => $jadwal->sisa_kuota + $pemesanan->jumlah_peserta
                        ]);
                    }
                } 
                elseif ($transactionStatus == 'pending') {
                    // Biarkan status tetap PENDING
                    $pembayaranUpdate['status_transaksi'] = 'PENDING';
                    $pembayaran->update($pembayaranUpdate);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Webhook processed'
                ], 200);
            });

        } catch (\Exception $e) {
            Log::error("Webhook Midtrans Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses webhook: ' . $e->getMessage()
            ], 500);
        }
    }
}
