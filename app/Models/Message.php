<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'id_message';

    protected $fillable = [
        'id_room',
        'sender_role',
        'sender_id',
        'message',
        'is_read'
    ];

    protected $appends = ['sender_type'];

    /**
     * Accessor for sender_type to ensure compatibility with existing templates.
     */
    public function getSenderTypeAttribute()
    {
        return $this->sender_role;
    }

    /**
     * Get the room this message belongs to.
     */
    public function room()
    {
        return $this->belongsTo(ChatRoom::class, 'id_room', 'id_room');
    }

    /**
     * Get the sender model.
     */
    public function sender()
    {
        if ($this->sender_role === 'customer') {
            return $this->belongsTo(Customer::class, 'sender_id', 'id_customer');
        } elseif ($this->sender_role === 'trip_leader') {
            return $this->belongsTo(TripLeader::class, 'sender_id', 'id_leader');
        } elseif ($this->sender_role === 'admin') {
            return $this->belongsTo(Admin::class, 'sender_id', 'id_admin');
        }
        return null;
    }
}
