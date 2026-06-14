<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class CustomerChatWebController extends Controller
{
    /**
     * Retrieve all chat messages for the logged-in customer.
     */
    public function getMessages()
    {
        $customerId = auth()->id();

        // Get all rooms where this customer is a participant
        $roomIds = ChatParticipant::where('role_type', 'customer')
            ->where('role_id', $customerId)
            ->pluck('id_room');

        // Mark incoming admin & trip leader messages as read in these rooms
        Message::whereIn('id_room', $roomIds)
            ->whereIn('sender_role', ['admin', 'trip_leader'])
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Fetch all messages from these rooms
        $messages = Message::whereIn('id_room', $roomIds)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a new message to the administrator/CS or the active Trip Leader.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $customerId = auth()->id();

        // Get all rooms where this customer is a participant
        $roomIds = ChatParticipant::where('role_type', 'customer')
            ->where('role_id', $customerId)
            ->pluck('id_room');

        // Find the last incoming message cross all rooms to determine who to reply to
        $lastIncoming = Message::whereIn('id_room', $roomIds)
            ->whereIn('sender_role', ['admin', 'trip_leader'])
            ->orderBy('created_at', 'desc')
            ->first();

        $targetRole = 'admin';
        $targetId = 1; // Default Admin ID

        if ($lastIncoming && $lastIncoming->sender_role === 'trip_leader') {
            $targetRole = 'trip_leader';
            $targetId = $lastIncoming->sender_id;
        }

        // Determine booking code if target is trip leader
        $bookingCode = null;
        if ($targetRole === 'trip_leader') {
            $booking = Pemesanan::where('id_customer', $customerId)
                ->where('status_pembayaran', 'PAID')
                ->whereHas('jadwalTrip', function ($q) use ($targetId) {
                    $q->where('id_leader', $targetId);
                })
                ->orderBy('tgl_pemesanan', 'desc')
                ->first();
            if ($booking) {
                $bookingCode = $booking->booking_code;
            }
        }

        $roomName = $targetRole === 'admin' 
            ? "Chat Customer #{$customerId} & Admin CS"
            : "Chat Booking {$bookingCode} - Customer & Leader";

        $room = ChatRoom::getOrCreateRoomForTwo('customer', $customerId, $targetRole, $targetId, $bookingCode, $roomName);

        $message = Message::create([
            'id_room' => $room->id_room,
            'sender_role' => 'customer',
            'sender_id' => $customerId,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message, 201);
    }
}
