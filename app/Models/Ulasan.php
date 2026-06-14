<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_customer', 'id_jadwal', 'rating', 'komentar'])]
class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function jadwalTrip()
    {
        return $this->belongsTo(JadwalTrip::class, 'id_jadwal', 'id_jadwal');
    }
}
