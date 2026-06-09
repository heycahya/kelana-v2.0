<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_pemesanan', 'transaction_id', 'snap_token', 'tgl_pembayaran', 'jumlah_bayar', 'metode_pembayaran', 'bukti_pembayaran'])]
class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
