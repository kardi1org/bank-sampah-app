<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat Admin
        User::create([
            'name' => 'Administrator Bank Sampah',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Membuat 3 Petugas untuk testing insentif
        $petugas = ['Andi', 'Siti', 'Budi'];
        foreach ($petugas as $nama) {
            User::create([
                'name' => 'Petugas ' . $nama,
                'email' => strtolower($nama) . '@mail.com',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
            ]);
        }

        // Membuat contoh Nasabah
        User::create([
            'name' => 'Nasabah Budi Santoso',
            'email' => 'nasabah@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'nasabah',
        ]);
    }
}
