<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['nama_leader', 'no_telp', 'email', 'password'])]
#[Hidden(['password'])]
class TripLeader extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'trip_leaders';
    protected $primaryKey = 'id_leader';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name attribute.
     */
    public function getNameAttribute(): string
    {
        return $this->nama_leader;
    }
}
