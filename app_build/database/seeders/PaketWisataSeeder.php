<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaketWisata;

class PaketWisataSeeder extends Seeder
{
    public function run(): void
    {
        $paket = [
            [
                'nama_paket' => 'Eksplorasi Bromo Midnight Premium',
                'harga' => 350000,
                'deskripsi' => 'Saksikan magisnya matahari terbit dari Penanjakan 1, dilanjutkan petualangan menggunakan Jeep 4x4 melintasi Lautan Pasir dan Kawah Bromo. Paket ini sudah termasuk fotografer profesional.',
                'rute' => 'Probolinggo, Jawa Timur',
                'fasilitas' => 'Jeep 4x4, Tiket Masuk Bromo, Fotografer Profesional, Air Mineral'
            ],
            [
                'nama_paket' => 'Sailing Pulau Komodo 3D2N Luxury',
                'harga' => 2500000,
                'deskripsi' => 'Nikmati pelayaran mewah menggunakan kapal Phinisi. Kunjungi Pulau Padar, Pink Beach, dan temukan langsung habitat asli naga Komodo dengan fasilitas premium kelas satu.',
                'rute' => 'Labuan Bajo, NTT',
                'fasilitas' => 'Kapal Phinisi Luxury, Porter & Local Guide, Makan 3x Sehari, Tiket Taman Nasional'
            ],
            [
                'nama_paket' => 'Pendakian Rinjani Jalur Sembalun',
                'harga' => 1800000,
                'deskripsi' => 'Taklukkan gunung berapi tertinggi kedua di Indonesia. Fasilitas porter, makan bergizi 3x sehari, dan tenda premium memastikan kenyamanan pendakian Anda menuju puncak Anjani.',
                'rute' => 'Lombok, NTB',
                'fasilitas' => 'Porter & Guide, Peralatan Camping Premium, Makan 3x Sehari, Tiket TNGR'
            ],
            [
                'nama_paket' => 'Blue Fire Kawah Ijen & Baluran',
                'harga' => 650000,
                'deskripsi' => 'Saksikan fenomena langka api biru Kawah Ijen di dini hari, dilanjutkan dengan safari ala Afrika di Taman Nasional Baluran. Paket lengkap dengan transportasi AC.',
                'rute' => 'Banyuwangi, Jawa Timur',
                'fasilitas' => 'Transportasi AC, Tiket Masuk Kawah Ijen & Baluran, Masker Gas, Local Guide'
            ]
        ];

        foreach ($paket as $data) {
            PaketWisata::updateOrCreate(['nama_paket' => $data['nama_paket']], $data);
        }
    }
}
