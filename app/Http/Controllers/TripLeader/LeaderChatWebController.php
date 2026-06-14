<?php

namespace App\Http\Controllers\TripLeader;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\ChatRoom;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class LeaderChatWebController extends Controller
{
    /**
     * Show the mobile-friendly chat page for the Trip Leader.
     */
    public function index()
    {
        $leaderId = auth()->id();
        
        // Fetch customers who have PAID bookings on schedules led by this Trip Leader
        $customers = \App\Models\Customer::whereHas('pemesanan', function ($q) use ($leaderId) {
            $q->where('status_pembayaran', 'PAID')
              ->whereHas('jadwalTrip', function ($jQ) use ($leaderId) {
                  $jQ->where('id_leader', $leaderId);
              });
        })->get();

        return view('leader.chat', compact('customers'));
    }

    /**
     * Retrieve all messages between this Trip Leader and the chosen contact (Admin or Customer).
     */
    public function getMessages(Request $request)
    {
        $leaderId = auth()->id();
        $contactType = $request->query('contact_type', 'admin');
        $contactId = $request->query('contact_id', 1);

        $bookingCode = null;
        if ($contactType === 'customer') {
            $booking = Pemesanan::where('id_customer', $contactId)
                ->where('status_pembayaran', 'PAID')
                ->whereHas('jadwalTrip', function ($q) use ($leaderId) {
                    $q->where('id_leader', $leaderId);
                })
                ->orderBy('tgl_pemesanan', 'desc')
                ->first();
            if ($booking) {
                $bookingCode = $booking->booking_code;
            }
        }

        $roomName = $contactType === 'admin'
            ? "Chat Leader #{$leaderId} & Admin CS"
            : "Chat Booking {$bookingCode} - Customer & Leader";

        $room = ChatRoom::getOrCreateRoomForTwo('trip_leader', $leaderId, $contactType, $contactId, $bookingCode, $roomName);

        // Mark incoming messages from this contact as read in this room
        Message::where('id_room', $room->id_room)
            ->where('sender_role', $contactType)
            ->where('sender_id', $contactId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where('id_room', $room->id_room)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a new message from the Trip Leader to the chosen contact.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'contact_type' => 'required|string|in:admin,customer',
            'contact_id' => 'required|integer'
        ]);

        $leaderId = auth()->id();
        $contactType = $request->contact_type;
        $contactId = $request->contact_id;

        $bookingCode = null;
        if ($contactType === 'customer') {
            $booking = Pemesanan::where('id_customer', $contactId)
                ->where('status_pembayaran', 'PAID')
                ->whereHas('jadwalTrip', function ($q) use ($leaderId) {
                    $q->where('id_leader', $leaderId);
                })
                ->orderBy('tgl_pemesanan', 'desc')
                ->first();
            if ($booking) {
                $bookingCode = $booking->booking_code;
            }
        }

        $roomName = $contactType === 'admin'
            ? "Chat Leader #{$leaderId} & Admin CS"
            : "Chat Booking {$bookingCode} - Customer & Leader";

        $room = ChatRoom::getOrCreateRoomForTwo('trip_leader', $leaderId, $contactType, $contactId, $bookingCode, $roomName);

        $message = Message::create([
            'id_room' => $room->id_room,
            'sender_role' => 'trip_leader',
            'sender_id' => $leaderId,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message, 201);
    }
}
