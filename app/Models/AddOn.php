<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nama_addon', 'harga', 'deskripsi'])]
class AddOn extends Model
{
    public function pemesanan()
    {
        return $this->belongsToMany(Pemesanan::class, 'pemesanan_addon', 'add_on_id', 'pemesanan_id', 'id', 'id_pemesanan')
            ->using(PemesananAddon::class)
            ->withPivot('kuantitas', 'subtotal')
            ->withTimestamps();
    }
}
