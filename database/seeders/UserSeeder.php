<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun ADMIN (Bisa Segalanya)
        User::create([
            'name' => 'Admin Arsip',
            'email' => 'admin@bpk.internal', 
            'password' => Hash::make('password_rahasia'), // Nanti ganti yang susah
            'role' => 'admin',
        ]);

        // 2. Akun TAMU (Cuma Bisa Baca)
        User::create([
            'name' => 'Pemeriksa/Tamu',
            'email' => 'tamu@bpk.internal', 
            'password' => Hash::make('tamu123'),
            'role' => 'guest',
        ]);
    }
}