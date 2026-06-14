<?php

namespace App\Http\Controllers\TripLeader;

use App\Http\Controllers\Controller;
use App\Models\Message;
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

        // Mark incoming messages from this contact as read
        Message::where('sender_type', $contactType)
            ->where('sender_id', $contactId)
            ->where('receiver_type', 'trip_leader')
            ->where('receiver_id', $leaderId)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($leaderId, $contactType, $contactId) {
            $q->where('sender_type', 'trip_leader')
              ->where('sender_id', $leaderId)
              ->where('receiver_type', $contactType)
              ->where('receiver_id', $contactId);
        })->orWhere(function ($q) use ($leaderId, $contactType, $contactId) {
            $q->where('sender_type', $contactType)
              ->where('sender_id', $contactId)
              ->where('receiver_type', 'trip_leader')
              ->where('receiver_id', $leaderId);
        })->orderBy('created_at', 'asc')->get();

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

        $message = Message::create([
            'sender_type' => 'trip_leader',
            'sender_id' => auth()->id(),
            'receiver_type' => $request->contact_type,
            'receiver_id' => $request->contact_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message, 201);
    }
}
