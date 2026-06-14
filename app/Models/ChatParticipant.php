<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $table = 'chat_participants';
    protected $primaryKey = 'id_participant';

    protected $fillable = [
        'id_room',
        'role_type',
        'role_id'
    ];

    public function room()
    {
        return $this->belongsTo(ChatRoom::class, 'id_room', 'id_room');
    }

    public function user()
    {
        if ($this->role_type === 'customer') {
            return $this->belongsTo(Customer::class, 'role_id', 'id_customer');
        } elseif ($this->role_type === 'trip_leader') {
            return $this->belongsTo(TripLeader::class, 'role_id', 'id_leader');
        } elseif ($this->role_type === 'admin') {
            return $this->belongsTo(Admin::class, 'role_id', 'id_admin');
        }
        return null;
    }
}
