<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_paket', 'deskripsi', 'harga', 'rute', 'fasilitas'])]
class PaketWisata extends Model
{
    use HasFactory;

    protected $table = 'paket_wisata';
    protected $primaryKey = 'id_paket';

    public function jadwalTrip()
    {
        return $this->hasMany(JadwalTrip::class, 'id_paket', 'id_paket');
    }

    public function reviews()
    {
        return $this->hasManyThrough(
            Ulasan::class,
            JadwalTrip::class,
            'id_paket', // Foreign key on jadwal_trip table
            'id_jadwal', // Foreign key on ulasan table
            'id_paket', // Local key on paket_wisata table
            'id_jadwal' // Local key on jadwal_trip table
        );
    }
}
