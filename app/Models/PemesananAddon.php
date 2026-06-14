<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PemesananAddon extends Pivot
{
    protected $table = 'pemesanan_addon';
    protected $primaryKey = 'id_pemesanan_addon';
    public $incrementing = true;
}
