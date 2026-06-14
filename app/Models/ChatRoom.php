<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_rooms';
    protected $primaryKey = 'id_room';

    protected $fillable = [
        'booking_code',
        'nama_room'
    ];

    public function participants()
    {
        return $this->hasMany(ChatParticipant::class, 'id_room', 'id_room');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'id_room', 'id_room');
    }

    /**
     * Get or create a room for two participants.
     */
    public static function getOrCreateRoomForTwo($role1, $id1, $role2, $id2, $bookingCode = null, $roomName = null)
    {
        $query = self::query();
        if ($bookingCode) {
            $query->where('booking_code', $bookingCode);
        } else {
            $query->whereNull('booking_code');
        }

        $room = $query->whereHas('participants', function ($q) use ($role1, $id1) {
            $q->where('role_type', $role1)->where('role_id', $id1);
        })->whereHas('participants', function ($q) use ($role2, $id2) {
            $q->where('role_type', $role2)->where('role_id', $id2);
        })->first();

        if (!$room) {
            if (!$roomName) {
                $roomName = "Room " . ucfirst($role1) . " #{$id1} & " . ucfirst($role2) . " #{$id2}";
            }
            
            $room = self::create([
                'booking_code' => $bookingCode,
                'nama_room' => $roomName
            ]);

            $room->participants()->create([
                'role_type' => $role1,
                'role_id' => $id1
            ]);

            $room->participants()->create([
                'role_type' => $role2,
                'role_id' => $id2
            ]);
        }

        return $room;
    }
}
