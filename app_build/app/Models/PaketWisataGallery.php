<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['paket_wisata_id', 'image_url', 'is_primary'])]
class PaketWisataGallery extends Model
{
    public function paketWisata()
    {
        return $this->belongsTo(PaketWisata::class, 'paket_wisata_id', 'id_paket');
    }
}
