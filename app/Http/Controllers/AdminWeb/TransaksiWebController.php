<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class TransaksiWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan::with(['customer', 'jadwalTrip.paketWisata'])
            ->orderBy('created_at', 'desc');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cQ) use ($search) {
                      $cQ->where('nama_customer', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status_pembayaran', $request->status);
        }

        $transaksis = $query->paginate(10);

        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|string|in:PENDING,PAID,FAILED,CANCELLED',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);
        $oldStatus = $pemesanan->status_pembayaran;
        $newStatus = $request->status_pembayaran;
        
        $pemesanan->status_pembayaran = $newStatus;
        $pemesanan->save();

        // Send automated CS bot message on status change
        if ($oldStatus !== $newStatus) {
            $msgContent = "";
            if ($newStatus === 'PAID') {
                $msgContent = "Pembayaran Anda untuk Kode Booking {$pemesanan->booking_code} telah berhasil diverifikasi oleh Admin! Status pemesanan Anda kini telah berubah menjadi PAID. Anda dapat mendownload E-Ticket PDF Anda langsung di menu 'Tiket Saya'. Selamat berpetualang!";
                
                // Trip Leader automatically contacts CS/Admin and Customer
                $pemesanan->load(['jadwalTrip.paketWisata', 'jadwalTrip.tripLeader', 'customer']);
                if ($pemesanan->jadwalTrip && $pemesanan->jadwalTrip->id_leader) {
                    $leader = $pemesanan->jadwalTrip->tripLeader;
                    if ($leader) {
                        $paketName = $pemesanan->jadwalTrip->paketWisata->nama_paket ?? 'Trip Wisata';
                        $leaderName = $leader->nama_leader ?? 'Trip Leader';
                        
                        // Send confirmation to Admin (CS)
                        $leaderMessage = "Halo CS/Admin, saya {$leaderName} selaku Trip Leader untuk paket {$paketName} (Booking Code: {$pemesanan->booking_code}). Saya mengonfirmasi bahwa saya siap bertugas memandu trip ini!";
                        $roomLeaderAdminName = "Chat Leader #{$leader->id_leader} & Admin CS";
                        $roomLeaderAdmin = \App\Models\ChatRoom::getOrCreateRoomForTwo('admin', 1, 'trip_leader', $leader->id_leader, null, $roomLeaderAdminName);
                        \App\Models\Message::create([
                            'id_room' => $roomLeaderAdmin->id_room,
                            'sender_role' => 'trip_leader',
                            'sender_id' => $leader->id_leader,
                            'message' => $leaderMessage,
                            'is_read' => false
                        ]);

                        // Send confirmation to Customer
                        $custName = $pemesanan->customer->nama_customer ?? 'Kak';
                        $customerMessage = "Halo {$custName}, saya {$leaderName} selaku Trip Leader Anda untuk paket {$paketName}. Pembayaran Anda telah terverifikasi lunas (PAID). Saya siap memandu perjalanan Anda! Silakan balas chat ini langsung jika ada koordinasi teknis atau perlengkapan yang dibutuhkan.";
                        $roomLeaderCustName = "Chat Booking {$pemesanan->booking_code} - Customer & Leader";
                        $roomLeaderCust = \App\Models\ChatRoom::getOrCreateRoomForTwo('customer', $pemesanan->id_customer, 'trip_leader', $leader->id_leader, $pemesanan->booking_code, $roomLeaderCustName);
                        \App\Models\Message::create([
                            'id_room' => $roomLeaderCust->id_room,
                            'sender_role' => 'trip_leader',
                            'sender_id' => $leader->id_leader,
                            'message' => $customerMessage,
                            'is_read' => false
                        ]);
                    }
                }
            } elseif ($newStatus === 'CANCELLED') {
                $msgContent = "Pemesanan Anda dengan Kode Booking {$pemesanan->booking_code} telah dibatalkan. Jika Anda sudah mentransfer pembayaran, silakan hubungi CS kami dengan menyertakan bukti transaksi untuk koordinasi pengembalian dana (refund).";
            } elseif ($newStatus === 'FAILED') {
                $msgContent = "Pemesanan Anda dengan Kode Booking {$pemesanan->booking_code} dinyatakan gagal/tidak disetujui. Silakan hubungi CS kami untuk bantuan atau klaim pengembalian dana jika diperlukan.";
            }

            if (!empty($msgContent)) {
                $roomName = "Chat Customer #{$pemesanan->id_customer} & Admin CS";
                $room = \App\Models\ChatRoom::getOrCreateRoomForTwo('admin', 1, 'customer', $pemesanan->id_customer, null, $roomName);
                \App\Models\Message::create([
                    'id_room' => $room->id_room,
                    'sender_role' => 'admin',
                    'sender_id' => 1, // Default Admin ID
                    'message' => $msgContent,
                    'is_read' => false
                ]);
            }
        }

        return redirect()->route('admin.transaksi.index')->with('success', 'Status transaksi #' . $pemesanan->booking_code . ' berhasil diperbarui.');
    }
}
