<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\ChatRoom;
use App\Models\Customer;
use App\Models\TripLeader;
use Illuminate\Http\Request;

class ChatAdminWebController extends Controller
{
    /**
     * Show the main Admin Chat panel view.
     */
    public function index()
    {
        return view('admin.messages.index');
    }

    /**
     * Get the list of all contacts (Customers & Trip Leaders) for the Inbox side-drawer.
     */
    public function getContacts()
    {
        $contacts = collect();

        // 1. Fetch Customers
        $customers = Customer::all();
        foreach ($customers as $c) {
            // Find the room between Admin (1) and Customer
            $room = ChatRoom::whereHas('participants', function($q) use ($c) {
                $q->where('role_type', 'customer')->where('role_id', $c->id_customer);
            })->whereHas('participants', function($q) {
                $q->where('role_type', 'admin')->where('role_id', 1);
            })->first();

            $lastMsg = null;
            $unreadCount = 0;

            if ($room) {
                $lastMsg = Message::where('id_room', $room->id_room)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $unreadCount = Message::where('id_room', $room->id_room)
                    ->where('sender_role', 'customer')
                    ->where('is_read', false)
                    ->count();
            }

            $contacts->push([
                'id' => $c->id_customer,
                'name' => $c->nama_customer,
                'email' => $c->email,
                'type' => 'customer',
                'avatar' => null,
                'last_message' => $lastMsg ? $lastMsg->message : '',
                'last_message_time' => $lastMsg ? $lastMsg->created_at->toDateTimeString() : '',
                'unread_count' => $unreadCount
            ]);
        }

        // 2. Fetch Trip Leaders
        $leaders = TripLeader::all();
        foreach ($leaders as $l) {
            // Find the room between Admin (1) and Trip Leader
            $room = ChatRoom::whereHas('participants', function($q) use ($l) {
                $q->where('role_type', 'trip_leader')->where('role_id', $l->id_leader);
            })->whereHas('participants', function($q) {
                $q->where('role_type', 'admin')->where('role_id', 1);
            })->first();

            $lastMsg = null;
            $unreadCount = 0;

            if ($room) {
                $lastMsg = Message::where('id_room', $room->id_room)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $unreadCount = Message::where('id_room', $room->id_room)
                    ->where('sender_role', 'trip_leader')
                    ->where('is_read', false)
                    ->count();
            }

            $contacts->push([
                'id' => $l->id_leader,
                'name' => $l->nama_leader,
                'email' => $l->email,
                'type' => 'trip_leader',
                'avatar' => $l->avatar,
                'last_message' => $lastMsg ? $lastMsg->message : '',
                'last_message_time' => $lastMsg ? $lastMsg->created_at->toDateTimeString() : '',
                'unread_count' => $unreadCount
            ]);
        }

        // Sort contacts: Contacts with recent messages appear first
        $contacts = $contacts->sort(function ($a, $b) {
            if ($a['last_message_time'] === $b['last_message_time']) {
                return strcmp($a['name'], $b['name']);
            }
            return strcmp($b['last_message_time'], $a['last_message_time']);
        })->values();

        return response()->json($contacts);
    }

    /**
     * Retrieve the chat thread between Admin and a specific contact.
     */
    public function getMessages($contact_type, $contact_id)
    {
        $roomName = "Chat " . ucfirst($contact_type) . " #{$contact_id} & Admin CS";
        $room = ChatRoom::getOrCreateRoomForTwo('admin', 1, $contact_type, $contact_id, null, $roomName);

        // Mark incoming messages from this contact as read in this room
        Message::where('id_room', $room->id_room)
            ->where('sender_role', $contact_type)
            ->where('sender_id', $contact_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where('id_room', $room->id_room)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a response message from Admin to a specific contact.
     */
    public function sendMessage($contact_type, $contact_id, Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $roomName = "Chat " . ucfirst($contact_type) . " #{$contact_id} & Admin CS";
        $room = ChatRoom::getOrCreateRoomForTwo('admin', 1, $contact_type, $contact_id, null, $roomName);

        $message = Message::create([
            'id_room' => $room->id_room,
            'sender_role' => 'admin',
            'sender_id' => auth()->id() ?? 1, // Default Admin ID fallback
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message, 201);
    }
}
