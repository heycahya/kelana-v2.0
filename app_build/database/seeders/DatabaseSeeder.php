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
    }
}
