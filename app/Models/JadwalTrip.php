<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_paket', 'id_leader', 'tanggal_mulai', 'tanggal_selesai', 'kuota', 'sisa_kuota', 'status_trip'])]
class JadwalTrip extends Model
{
    use HasFactory;

    protected $table = 'jadwal_trip';
    protected $primaryKey = 'id_jadwal';

    public function paketWisata()
    {
        return $this->belongsTo(PaketWisata::class, 'id_paket', 'id_paket');
    }

    public function tripLeader()
    {
        return $this->belongsTo(TripLeader::class, 'id_leader', 'id_leader');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_jadwal', 'id_jadwal');
    }
}
