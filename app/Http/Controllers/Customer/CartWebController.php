<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\JadwalTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartWebController extends Controller
{
    /**
     * Get the latest pending booking for the customer (acting as cart).
     */
    public function index()
    {
        $customerId = Auth::guard('customer')->id();
        if (!$customerId) {
            return response()->json(null, 401);
        }

        $pendingBooking = Pemesanan::where('id_customer', $customerId)
            ->where('status_pembayaran', 'PENDING')
            ->with(['jadwal.paketWisata', 'pembayaran'])
            ->latest('id_pemesanan')
            ->first();

        if (!$pendingBooking) {
            return response()->json(null);
        }

        return response()->json([
            'id_pemesanan' => $pendingBooking->id_pemesanan,
            'booking_code' => $pendingBooking->booking_code,
            'paket_nama' => $pendingBooking->jadwal?->paketWisata?->nama_paket ?? 'Trip Package',
            'tanggal_keberangkatan' => $pendingBooking->jadwal ? \Carbon\Carbon::parse($pendingBooking->jadwal->tanggal_mulai)->format('d M Y') : '',
            'jumlah_peserta' => $pendingBooking->jumlah_peserta,
            'total_harga' => $pendingBooking->total_harga,
            'snap_token' => $pendingBooking->pembayaran?->snap_token,
        ]);
    }

    /**
     * Cancel a pending booking.
     */
    public function destroy($id)
    {
        $customerId = Auth::guard('customer')->id();
        if (!$customerId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        DB::beginTransaction();
        try {
            $booking = Pemesanan::where('id_customer', $customerId)
                ->where('id_pemesanan', $id)
                ->where('status_pembayaran', 'PENDING')
                ->first();

            if (!$booking) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Order not found or already processed'], 404);
            }

            // Restore quota with DB locking to prevent race conditions
            $jadwal = JadwalTrip::lockForUpdate()->find($booking->id_jadwal);
            if ($jadwal) {
                $jadwal->update([
                    'sisa_kuota' => $jadwal->sisa_kuota + $booking->jumlah_peserta
                ]);
            }

            // Mark booking as CANCELLED
            $booking->update([
                'status_pembayaran' => 'CANCELLED'
            ]);

            DB::commit();
            session()->flash('success', 'Pesanan Anda berhasil dibatalkan.');
            return response()->json(['status' => 'success', 'message' => 'Pesanan berhasil dibatalkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()], 500);
        }
    }
}
