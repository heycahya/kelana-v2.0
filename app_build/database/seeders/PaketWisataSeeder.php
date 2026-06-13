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
                'fasilitas' => 'Jeep 4x4, Tiket Masuk Bromo, Fotografer Profesional, Air Mineral',
                'latitude' => '-7.942493',
                'longitude' => '112.953012',
                'galleries' => [
                    ['image_url' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=800&q=80', 'is_primary' => true],
                    ['image_url' => 'https://images.unsplash.com/photo-1533587851505-d119e13bf0eb?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                ]
            ],
            [
                'nama_paket' => 'Sailing Pulau Komodo 3D2N Luxury',
                'harga' => 2500000,
                'deskripsi' => 'Nikmati pelayaran mewah menggunakan kapal Phinisi. Kunjungi Pulau Padar, Pink Beach, dan temukan langsung habitat asli naga Komodo dengan fasilitas premium kelas satu.',
                'rute' => 'Labuan Bajo, NTT',
                'fasilitas' => 'Kapal Phinisi Luxury, Porter & Local Guide, Makan 3x Sehari, Tiket Taman Nasional',
                'latitude' => '-8.625619',
                'longitude' => '119.529272',
                'galleries' => [
                    ['image_url' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=800&q=80', 'is_primary' => true],
                    ['image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                ]
            ],
            [
                'nama_paket' => 'Pendakian Rinjani Jalur Sembalun',
                'harga' => 1800000,
                'deskripsi' => 'Taklukkan gunung berapi tertinggi kedua di Indonesia. Fasilitas porter, makan bergizi 3x sehari, dan tenda premium memastikan kenyamanan pendakian Anda menuju puncak Anjani.',
                'rute' => 'Lombok, NTB',
                'fasilitas' => 'Porter & Guide, Peralatan Camping Premium, Makan 3x Sehari, Tiket TNGR',
                'latitude' => '-8.411634',
                'longitude' => '116.457813',
                'galleries' => [
                    ['image_url' => 'https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=800&q=80', 'is_primary' => true],
                    ['image_url' => 'https://images.unsplash.com/photo-1501555088652-021faa106b9b?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                ]
            ],
            [
                'nama_paket' => 'Blue Fire Kawah Ijen & Baluran',
                'harga' => 650000,
                'deskripsi' => 'Saksikan fenomena langka api biru Kawah Ijen di dini hari, dilanjutkan dengan safari ala Afrika di Taman Nasional Baluran. Paket lengkap dengan transportasi AC.',
                'rute' => 'Banyuwangi, Jawa Timur',
                'fasilitas' => 'Transportasi AC, Tiket Masuk Kawah Ijen & Baluran, Masker Gas, Local Guide',
                'latitude' => '-8.058564',
                'longitude' => '114.242462',
                'galleries' => [
                    ['image_url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=800&q=80', 'is_primary' => true],
                    ['image_url' => 'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1472214222541-d510753a8707?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                    ['image_url' => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=400&q=80', 'is_primary' => false],
                ]
            ]
        ];

        foreach ($paket as $data) {
            $galleries = $data['galleries'];
            unset($data['galleries']);
            
            $paketWisata = PaketWisata::updateOrCreate(['nama_paket' => $data['nama_paket']], $data);
            
            foreach ($galleries as $galleryData) {
                $paketWisata->galleries()->updateOrCreate(
                    ['image_url' => $galleryData['image_url']],
                    ['is_primary' => $galleryData['is_primary']]
                );
            }
        }
    }
}
