<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
use App\Models\Customer;
use App\Models\JadwalTrip;
use App\Models\PaketWisata;
use App\Models\TripLeader;
use Carbon\Carbon;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        // No dummy bookings seeded to keep database transactions clean.
    }
}
