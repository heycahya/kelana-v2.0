<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Message;
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
            $lastMsg = Message::where(function ($q) use ($c) {
                $q->where('sender_type', 'customer')->where('sender_id', $c->id_customer);
            })->orWhere(function ($q) use ($c) {
                $q->where('receiver_type', 'customer')->where('receiver_id', $c->id_customer);
            })->orderBy('created_at', 'desc')->first();

            $unreadCount = Message::where('sender_type', 'customer')
                ->where('sender_id', $c->id_customer)
                ->where('receiver_type', 'admin')
                ->where('is_read', false)
                ->count();

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
            $lastMsg = Message::where(function ($q) use ($l) {
                $q->where('sender_type', 'trip_leader')->where('sender_id', $l->id_leader);
            })->orWhere(function ($q) use ($l) {
                $q->where('receiver_type', 'trip_leader')->where('receiver_id', $l->id_leader);
            })->orderBy('created_at', 'desc')->first();

            $unreadCount = Message::where('sender_type', 'trip_leader')
                ->where('sender_id', $l->id_leader)
                ->where('receiver_type', 'admin')
                ->where('is_read', false)
                ->count();

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
        // Mark incoming messages from this contact as read
        Message::where('sender_type', $contact_type)
            ->where('sender_id', $contact_id)
            ->where('receiver_type', 'admin')
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($contact_type, $contact_id) {
            $q->where('sender_type', $contact_type)
              ->where('sender_id', $contact_id)
              ->where('receiver_type', 'admin');
        })->orWhere(function ($q) use ($contact_type, $contact_id) {
            $q->where('sender_type', 'admin')
              ->where('receiver_type', $contact_type)
              ->where('receiver_id', $contact_id);
        })->orderBy('created_at', 'asc')->get();

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

        $message = Message::create([
            'sender_type' => 'admin',
            'sender_id' => auth()->id() ?? 1, // Default Admin ID fallback
            'receiver_type' => $contact_type,
            'receiver_id' => $contact_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message, 201);
    }
}
