<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'sender_type',
        'sender_id',
        'receiver_type',
        'receiver_id',
        'message',
        'is_read'
    ];

    /**
     * Get the sender model.
     */
    public function sender()
    {
        if ($this->sender_type === 'customer') {
            return $this->belongsTo(Customer::class, 'sender_id', 'id_customer');
        } elseif ($this->sender_type === 'trip_leader') {
            return $this->belongsTo(TripLeader::class, 'sender_id', 'id_leader');
        } elseif ($this->sender_type === 'admin') {
            return $this->belongsTo(Admin::class, 'sender_id', 'id_admin');
        }
        return null;
    }

    /**
     * Get the receiver model.
     */
    public function receiver()
    {
        if ($this->receiver_type === 'customer') {
            return $this->belongsTo(Customer::class, 'receiver_id', 'id_customer');
        } elseif ($this->receiver_type === 'trip_leader') {
            return $this->belongsTo(TripLeader::class, 'receiver_id', 'id_leader');
        } elseif ($this->receiver_type === 'admin') {
            return $this->belongsTo(Admin::class, 'receiver_id', 'id_admin');
        }
        return null;
    }
}
