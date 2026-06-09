<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\TripLeader;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        Admin::create([
            'username' => 'admin.kelana',
            'password' => Hash::make('PasswordAdmin123!'),
            'nama_admin' => 'Admin Kelana'
        ]);

        // 2. Seed Customer
        Customer::create([
            'nama_customer' => 'Budi Santoso',
            'email' => 'budi.santoso@kelana.com',
            'password' => Hash::make('PasswordCustomer123!'),
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 45'
        ]);

        // 3. Seed Trip Leader
        TripLeader::create([
            'nama_leader' => 'Adi Wijaya',
            'no_telp' => '089876543210',
            'email' => 'adi.wijaya@kelana.com',
            'password' => Hash::make('PasswordLeader123!')
        ]);

        // 4. Seed Paket Wisata
        \App\Models\PaketWisata::create([
            'nama_paket' => 'Trip Bromo Midnight',
            'deskripsi' => 'Open Trip Bromo Sunrise semi-private. Menikmati indahnya sunrise dari Penanjakan, berfoto di Bukit Cinta & Widodaren, pasir berbisik, kawah Bromo.',
            'harga' => 350000.00,
            'rute' => 'Malang - Tumpang - Wonokitri - Penanjakan - Bromo - Malang',
            'fasilitas' => 'Jeep Bromo, Tiket Masuk, Sopir & BBM, Air Mineral, Snack & Kopi pagi.'
        ]);

        \App\Models\PaketWisata::create([
            'nama_paket' => 'Trip Kawah Ijen Blue Fire',
            'deskripsi' => 'Open Trip Kawah Ijen Banyuwangi. Menyaksikan fenomena blue fire yang langka, sunrise di atas kawah, danau asam hijau toska.',
            'harga' => 450000.00,
            'rute' => 'Banyuwangi - Paltuding - Kawah Ijen - Banyuwangi',
            'fasilitas' => 'Transportasi AC, Tiket Masuk, Local Guide, Masker Gas, Air Mineral.'
        ]);
    }
}
