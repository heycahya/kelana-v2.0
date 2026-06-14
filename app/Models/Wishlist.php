<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['customer_id', 'paket_wisata_id'])]
class Wishlist extends Model
{
    protected $primaryKey = 'id_wishlist';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customer');
    }

    public function paketWisata()
    {
        return $this->belongsTo(PaketWisata::class, 'paket_wisata_id', 'id_paket');
    }
}
