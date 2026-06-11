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
        $customer = Customer::where('email', 'siti.rahma@kelana.com')->first();
        $customerId = $customer ? $customer->id_customer : 1;

        // Fetch seeded schedules
        $jadwalBromo = JadwalTrip::whereHas('paketWisata', function ($q) {
            $q->where('nama_paket', 'Eksplorasi Bromo Midnight Premium');
        })->first();

        $jadwalKomodo = JadwalTrip::whereHas('paketWisata', function ($q) {
            $q->where('nama_paket', 'Sailing Pulau Komodo 3D2N Luxury');
        })->first();

        if ($jadwalBromo) {
            Ulasan::updateOrCreate(
                ['id_customer' => $customerId, 'id_jadwal' => $jadwalBromo->id_jadwal],
                [
                    'rating' => 5,
                    'komentar' => 'Trip yang luar biasa! Pelayanan sangat memuaskan, pemandangan Bromo sangat indah, dan tour leader-nya sangat ramah dan informatif.'
                ]
            );
        }

        if ($jadwalKomodo) {
            Ulasan::updateOrCreate(
                ['id_customer' => $customerId, 'id_jadwal' => $jadwalKomodo->id_jadwal],
                [
                    'rating' => 5,
                    'komentar' => 'Sailing dengan Phinisi mewah yang luar biasa menyenangkan. Makanan enak sekali, pemandangan Labuan Bajo menakjubkan.'
                ]
            );
        }
    }
}
