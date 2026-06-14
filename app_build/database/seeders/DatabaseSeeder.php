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
        Admin::updateOrCreate(
            ['username' => 'admin.kelana'],
            [
                'password' => Hash::make('PasswordAdmin123!'),
                'nama_admin' => 'Admin Kelana'
            ]
        );

        // 2. Seed Customer
        Customer::updateOrCreate(
            ['email' => 'budi.santoso@kelana.com'],
            [
                'nama_customer' => 'Budi Santoso',
                'password' => Hash::make('PasswordCustomer123!'),
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 45'
            ]
        );
        // 3. Seed Trip Leader
        TripLeader::updateOrCreate(
            ['email' => 'adi.wijaya@kelana.com'],
            [
                'nama_leader' => 'Bima - Senior Guide Kelana',
                'no_telp' => '089876543210',
                'password' => Hash::make('PasswordLeader123!'),
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
                'bio' => 'Bima adalah Senior Guide Kelana bersertifikasi internasional dengan pengalaman lebih dari 8 tahun memandu pendakian gunung dan pelayaran ekspedisi di seluruh Nusantara.',
                'rating_akumulatif' => 4.9
            ]
        );

        // 4. Call external seeders
        $this->call([
            PaketWisataSeeder::class,
            JadwalTripSeeder::class,
            UlasanSeeder::class,
            AddOnSeeder::class,
            PemesananSeeder::class,
        ]);
    }
}
