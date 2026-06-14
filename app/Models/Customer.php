<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['nama_customer', 'email', 'password', 'no_telp', 'alamat', 'kontak_darurat'])]
#[Hidden(['password'])]
class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'customers';
    protected $primaryKey = 'id_customer';

    public function wishlists()
    {
        return $this->belongsToMany(PaketWisata::class, 'wishlists', 'customer_id', 'paket_wisata_id', 'id_customer', 'id_paket')
            ->withTimestamps();
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_customer', 'id_customer');
    }

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
        return $this->nama_customer;
    }
}
