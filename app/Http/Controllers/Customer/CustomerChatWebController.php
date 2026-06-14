<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class CustomerChatWebController extends Controller
{
    /**
     * Retrieve all chat messages for the logged-in customer.
     */
    public function getMessages()
    {
        $customerId = auth()->id();

        // Mark incoming admin & trip leader messages as read
        Message::whereIn('sender_type', ['admin', 'trip_leader'])
            ->where('receiver_type', 'customer')
            ->where('receiver_id', $customerId)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($customerId) {
            $q->where('sender_type', 'customer')
              ->where('sender_id', $customerId)
              ->whereIn('receiver_type', ['admin', 'trip_leader']);
        })->orWhere(function ($q) use ($customerId) {
            $q->whereIn('sender_type', ['admin', 'trip_leader'])
              ->where('receiver_type', 'customer')
              ->where('receiver_id', $customerId);
        })->orderBy('created_at', 'asc')->get();

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

        // Find the last incoming message to determine who to reply to
        $lastIncoming = Message::where('receiver_type', 'customer')
            ->where('receiver_id', $customerId)
            ->whereIn('sender_type', ['admin', 'trip_leader'])
            ->orderBy('created_at', 'desc')
            ->first();

        $receiverType = 'admin';
        $receiverId = 1; // Default Admin ID

        if ($lastIncoming && $lastIncoming->sender_type === 'trip_leader') {
            $receiverType = 'trip_leader';
            $receiverId = $lastIncoming->sender_id;
        }

        $message = Message::create([
            'sender_type' => 'customer',
            'sender_id' => $customerId,
            'receiver_type' => $receiverType,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message, 201);
    }
}
