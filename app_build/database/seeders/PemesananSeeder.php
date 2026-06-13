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
        $customer = Customer::where('email', 'budi.santoso@kelana.com')->first();
        if (!$customer) {
            return;
        }

        $leader = TripLeader::where('email', 'adi.wijaya@kelana.com')->first();
        $leaderId = $leader ? $leader->id_leader : 1;

        // 1. Seed active future trip (Bromo Midnight)
        $paketBromo = PaketWisata::where('nama_paket', 'Eksplorasi Bromo Midnight Premium')->first();
        if ($paketBromo) {
            $jadwalAktif = JadwalTrip::where('id_paket', $paketBromo->id_paket)
                ->where('tanggal_mulai', '>', Carbon::now()->toDateString())
                ->first();

            if ($jadwalAktif) {
                Pemesanan::updateOrCreate(
                    ['booking_code' => 'BK-BROMO-123'],
                    [
                        'id_customer' => $customer->id_customer,
                        'id_jadwal' => $jadwalAktif->id_jadwal,
                        'tgl_pemesanan' => Carbon::now()->subDays(1),
                        'jumlah_peserta' => 2,
                        'total_harga' => $paketBromo->harga * 2,
                        'status_pembayaran' => 'PAID',
                        'attendance_status' => 'belum_hadir',
                        'jumlah_hadir' => 0,
                        'qr_code_token' => 'TOKEN-BROMO-123'
                    ]
                );
            }
        }

        // 2. Seed past completed trip (Komodo or custom past Bromo trip)
        $paketKomodo = PaketWisata::where('nama_paket', 'Sailing Pulau Komodo 3D2N Luxury')->first();
        if ($paketKomodo) {
            // Create a past JadwalTrip for this completed trip
            $jadwalSelesai = JadwalTrip::updateOrCreate(
                [
                    'id_paket' => $paketKomodo->id_paket,
                    'tanggal_mulai' => Carbon::now()->subDays(15)->format('Y-m-d')
                ],
                [
                    'id_leader' => $leaderId,
                    'tanggal_selesai' => Carbon::now()->subDays(13)->format('Y-m-d'),
                    'kuota' => 12,
                    'sisa_kuota' => 8,
                    'status_trip' => 'Selesai'
                ]
            );

            Pemesanan::updateOrCreate(
                ['booking_code' => 'BK-KOMODO-PAST'],
                [
                    'id_customer' => $customer->id_customer,
                    'id_jadwal' => $jadwalSelesai->id_jadwal,
                    'tgl_pemesanan' => Carbon::now()->subDays(18),
                    'jumlah_peserta' => 1,
                    'total_harga' => $paketKomodo->harga,
                    'status_pembayaran' => 'PAID',
                    'attendance_status' => 'hadir',
                    'jumlah_hadir' => 1,
                    'qr_code_token' => 'TOKEN-KOMODO-PAST'
                ]
            );
        }
    }
}
