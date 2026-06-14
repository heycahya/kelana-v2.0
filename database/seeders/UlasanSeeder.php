<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ulasan;
use App\Models\JadwalTrip;
use App\Models\Customer;

class UlasanSeeder extends Seeder
{
    public function run(): void
    {
        $dewi = Customer::updateOrCreate(
            ['email' => 'dewi.seeder@kelana.com'],
            [
                'nama_customer' => 'Dewi Seeder',
                'password' => bcrypt('PasswordCustomer123!'),
                'no_telp' => '081234567891',
                'alamat' => 'Jl. Diponegoro No. 12'
            ]
        );
        $dewiId = $dewi->id_customer;

        // Fetch seeded schedules
        $jadwalBromo = JadwalTrip::whereHas('paketWisata', function ($q) {
            $q->where('nama_paket', 'Eksplorasi Bromo Midnight Premium');
        })->first();

        $jadwalKomodo = JadwalTrip::whereHas('paketWisata', function ($q) {
            $q->where('nama_paket', 'Sailing Pulau Komodo 3D2N Luxury');
        })->first();

        if ($jadwalBromo) {
            Ulasan::updateOrCreate(
                ['id_customer' => $dewiId, 'id_jadwal' => $jadwalBromo->id_jadwal],
                [
                    'rating' => 5,
                    'komentar' => 'Trip yang luar biasa! Pelayanan sangat memuaskan, pemandangan Bromo sangat indah, dan tour leader-nya sangat ramah dan informatif.'
                ]
            );
        }

        if ($jadwalKomodo) {
            Ulasan::updateOrCreate(
                ['id_customer' => $dewiId, 'id_jadwal' => $jadwalKomodo->id_jadwal],
                [
                    'rating' => 5,
                    'komentar' => 'Sailing dengan Phinisi mewah yang luar biasa menyenangkan. Makanan enak sekali, pemandangan Labuan Bajo menakjubkan.'
                ]
            );
        }
    }
}
