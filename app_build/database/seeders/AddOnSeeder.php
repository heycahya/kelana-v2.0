<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddOn;

class AddOnSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            [
                'nama_addon' => 'Sewa Jaket Gunung',
                'harga' => 50000,
                'deskripsi' => 'Jaket windproof & waterproof standar pendakian'
            ],
            [
                'nama_addon' => 'Sewa Tas Carrier 60L',
                'harga' => 80000,
                'deskripsi' => 'Carrier bag ergonomis kapasitas 60 liter'
            ],
            [
                'nama_addon' => 'Sewa Sleeping Bag',
                'harga' => 30000,
                'deskripsi' => 'Sleeping bag thermal ultra-warm'
            ],
            [
                'nama_addon' => 'Dokumentasi Drone & GoPro',
                'harga' => 150000,
                'deskripsi' => 'Foto & video udara profesional serta dokumentasi aksi'
            ]
        ];

        foreach ($addons as $addon) {
            AddOn::updateOrCreate(['nama_addon' => $addon['nama_addon']], $addon);
        }
    }
}
