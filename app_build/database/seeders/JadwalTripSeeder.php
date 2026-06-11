<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalTrip;
use App\Models\PaketWisata;
use App\Models\TripLeader;
use Carbon\Carbon;

class JadwalTripSeeder extends Seeder
{
    public function run(): void
    {
        $leader = TripLeader::where('email', 'adi.wijaya@kelana.com')->first();
        $leaderId = $leader ? $leader->id_leader : 1;

        $paketBromo = PaketWisata::where('nama_paket', 'Eksplorasi Bromo Midnight Premium')->first();
        $paketKomodo = PaketWisata::where('nama_paket', 'Sailing Pulau Komodo 3D2N Luxury')->first();
        $paketRinjani = PaketWisata::where('nama_paket', 'Pendakian Rinjani Jalur Sembalun')->first();
        $paketIjen = PaketWisata::where('nama_paket', 'Blue Fire Kawah Ijen & Baluran')->first();

        if ($paketBromo) {
            JadwalTrip::updateOrCreate(
                ['id_paket' => $paketBromo->id_paket, 'tanggal_mulai' => Carbon::now()->addDays(7)->format('Y-m-d')],
                [
                    'id_leader' => $leaderId,
                    'tanggal_selesai' => Carbon::now()->addDays(7)->format('Y-m-d'),
                    'kuota' => 15,
                    'sisa_kuota' => 10,
                    'status_trip' => 'Open'
                ]
            );
            
            JadwalTrip::updateOrCreate(
                ['id_paket' => $paketBromo->id_paket, 'tanggal_mulai' => Carbon::now()->addDays(14)->format('Y-m-d')],
                [
                    'id_leader' => $leaderId,
                    'tanggal_selesai' => Carbon::now()->addDays(14)->format('Y-m-d'),
                    'kuota' => 20,
                    'sisa_kuota' => 5,
                    'status_trip' => 'Open'
                ]
            );
        }

        if ($paketKomodo) {
            JadwalTrip::updateOrCreate(
                ['id_paket' => $paketKomodo->id_paket, 'tanggal_mulai' => Carbon::now()->addDays(30)->format('Y-m-d')],
                [
                    'id_leader' => $leaderId,
                    'tanggal_selesai' => Carbon::now()->addDays(32)->format('Y-m-d'),
                    'kuota' => 12,
                    'sisa_kuota' => 8,
                    'status_trip' => 'Open'
                ]
            );
        }

        if ($paketRinjani) {
            JadwalTrip::updateOrCreate(
                ['id_paket' => $paketRinjani->id_paket, 'tanggal_mulai' => Carbon::now()->addDays(20)->format('Y-m-d')],
                [
                    'id_leader' => $leaderId,
                    'tanggal_selesai' => Carbon::now()->addDays(23)->format('Y-m-d'),
                    'kuota' => 10,
                    'sisa_kuota' => 6,
                    'status_trip' => 'Open'
                ]
            );
        }

        if ($paketIjen) {
            JadwalTrip::updateOrCreate(
                ['id_paket' => $paketIjen->id_paket, 'tanggal_mulai' => Carbon::now()->addDays(10)->format('Y-m-d')],
                [
                    'id_leader' => $leaderId,
                    'tanggal_selesai' => Carbon::now()->addDays(11)->format('Y-m-d'),
                    'kuota' => 25,
                    'sisa_kuota' => 20,
                    'status_trip' => 'Open'
                ]
            );
        }
    }
}
