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
     * Get the list of all chat contacts for the Customer.
     */
    public function getContacts()
    {
        $customerId = auth()->id();
        $contacts = collect();

        // 1. Fetch Admin CS Support contact
        $adminRoom = ChatRoom::whereHas('participants', function($q) use ($customerId) {
            $q->where('role_type', 'customer')->where('role_id', $customerId);
        })->whereHas('participants', function($q) {
            $q->where('role_type', 'admin')->where('role_id', 1);
        })->first();

        $adminLastMsg = null;
        $adminUnreadCount = 0;

        if ($adminRoom) {
            $adminLastMsg = Message::where('id_room', $adminRoom->id_room)
                ->orderBy('created_at', 'desc')
                ->first();

            $adminUnreadCount = Message::where('id_room', $adminRoom->id_room)
                ->where('sender_role', 'admin')
                ->where('is_read', false)
                ->count();
        }

        $contacts->push([
            'id' => 1,
            'name' => 'Kelana Customer Service',
            'type' => 'admin',
            'role_type' => 'admin',
            'avatar' => null,
            'status' => '🟢 Online - Admin Support',
            'last_message' => $adminLastMsg ? $adminLastMsg->message : '',
            'last_message_time' => $adminLastMsg ? $adminLastMsg->created_at->toDateTimeString() : '',
            'unread_count' => $adminUnreadCount
        ]);

        // 2. Fetch Trip Leaders who are assigned to PAID bookings of this customer
        $bookings = Pemesanan::where('id_customer', $customerId)
            ->where('status_pembayaran', 'PAID')
            ->whereHas('jadwalTrip.tripLeader')
            ->with(['jadwalTrip.tripLeader', 'jadwalTrip.paketWisata'])
            ->get();

        $groupedBookings = $bookings->groupBy('jadwalTrip.id_leader');

        foreach ($groupedBookings as $leaderId => $leaderBookings) {
            $booking = $leaderBookings->first();
            $leader = $booking->jadwalTrip->tripLeader;
            $packageName = $booking->jadwalTrip->paketWisata->nama_paket ?? '';

            $leaderRoom = ChatRoom::whereHas('participants', function($q) use ($customerId) {
                $q->where('role_type', 'customer')->where('role_id', $customerId);
            })->whereHas('participants', function($q) use ($leaderId) {
                $q->where('role_type', 'trip_leader')->where('role_id', $leaderId);
            })->first();

            $leaderLastMsg = null;
            $leaderUnreadCount = 0;

            if ($leaderRoom) {
                $leaderLastMsg = Message::where('id_room', $leaderRoom->id_room)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $leaderUnreadCount = Message::where('id_room', $leaderRoom->id_room)
                    ->where('sender_role', 'trip_leader')
                    ->where('is_read', false)
                    ->count();
            }

            $contacts->push([
                'id' => $leader->id_leader,
                'name' => $leader->nama_leader,
                'type' => 'trip_leader',
                'role_type' => 'trip_leader',
                'avatar' => $leader->avatar,
                'status' => 'Trip Leader - ' . $packageName,
                'last_message' => $leaderLastMsg ? $leaderLastMsg->message : '',
                'last_message_time' => $leaderLastMsg ? $leaderLastMsg->created_at->toDateTimeString() : '',
                'unread_count' => $leaderUnreadCount
            ]);
        }

        // Sort: Admin CS always first, then others by last message time
        $adminContact = $contacts->shift();
        $sortedOthers = $contacts->sort(function ($a, $b) {
            if ($a['last_message_time'] === $b['last_message_time']) {
                return strcmp($a['name'], $b['name']);
            }
            return strcmp($b['last_message_time'], $a['last_message_time']);
        })->values();

        $sortedContacts = collect([$adminContact])->concat($sortedOthers);

        return response()->json($sortedContacts);
    }

    /**
     * Retrieve all chat messages for a specific contact room.
     */
    public function getMessages(Request $request)
    {
        $customerId = auth()->id();
        $contactType = $request->query('contact_type', 'admin');
        $contactId = $request->query('contact_id', 1);

        $bookingCode = null;
        if ($contactType === 'trip_leader') {
            $booking = Pemesanan::where('id_customer', $customerId)
                ->where('status_pembayaran', 'PAID')
                ->whereHas('jadwalTrip', function ($q) use ($contactId) {
                    $q->where('id_leader', $contactId);
                })
                ->orderBy('tgl_pemesanan', 'desc')
                ->first();
            if ($booking) {
                $bookingCode = $booking->booking_code;
            }
        }

        $roomName = $contactType === 'admin' 
            ? "Chat Customer #{$customerId} & Admin CS"
            : "Chat Booking {$bookingCode} - Customer & Leader";

        $room = ChatRoom::getOrCreateRoomForTwo('customer', $customerId, $contactType, $contactId, $bookingCode, $roomName);

        // Mark incoming messages from this contact as read in this room
        Message::where('id_room', $room->id_room)
            ->where('sender_role', $contactType)
            ->where('sender_id', $contactId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Fetch all messages from this room
        $messages = Message::where('id_room', $room->id_room)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a new message to the chosen contact.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'contact_type' => 'required|string|in:admin,trip_leader',
            'contact_id' => 'required|integer'
        ]);

        $customerId = auth()->id();
        $contactType = $request->contact_type;
        $contactId = $request->contact_id;

        $bookingCode = null;
        if ($contactType === 'trip_leader') {
            $booking = Pemesanan::where('id_customer', $customerId)
                ->where('status_pembayaran', 'PAID')
                ->whereHas('jadwalTrip', function ($q) use ($contactId) {
                    $q->where('id_leader', $contactId);
                })
                ->orderBy('tgl_pemesanan', 'desc')
                ->first();
            if ($booking) {
                $bookingCode = $booking->booking_code;
            }
        }

        $roomName = $contactType === 'admin' 
            ? "Chat Customer #{$customerId} & Admin CS"
            : "Chat Booking {$bookingCode} - Customer & Leader";

        $room = ChatRoom::getOrCreateRoomForTwo('customer', $customerId, $contactType, $contactId, $bookingCode, $roomName);

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
