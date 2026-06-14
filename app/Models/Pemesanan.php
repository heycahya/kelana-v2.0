<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pemesanan
 * 
 * PENTING: Kolom `total_harga` dan `diskon` berfungsi sebagai HISTORICAL SNAPSHOT
 * finansial dari saat pemesanan dibuat. Nilai ini bersifat statis/permanen dan
 * TIDAK BOLEH dihitung ulang secara real-time berdasarkan relasi harga paket
 * wisata atau promo aktif yang mungkin berubah di masa depan, demi menjaga
 * integritas data laporan keuangan dan audit transaksi.
 */
#[Fillable(['booking_code', 'id_customer', 'id_jadwal', 'tgl_pemesanan', 'jumlah_peserta', 'total_harga', 'status_pembayaran', 'attendance_status', 'jumlah_hadir', 'qr_code_token', 'total_biaya_addons', 'promo_code', 'diskon'])]
class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    public function addOns()
    {
        return $this->belongsToMany(AddOn::class, 'pemesanan_addon', 'pemesanan_id', 'add_on_id', 'id_pemesanan', 'id')
            ->using(PemesananAddon::class)
            ->withPivot('kuantitas', 'subtotal')
            ->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function jadwalTrip()
    {
        return $this->belongsTo(JadwalTrip::class, 'id_jadwal', 'id_jadwal');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalTrip::class, 'id_jadwal', 'id_jadwal');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }
}
