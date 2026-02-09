<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat Akun Admin
        User::create([
            'name' => 'Admin Arsip',
            'email' => 'admin@bpk.go.id',
            'password' => Hash::make('password_rahasia'), // Password login
            'role' => 'admin',
        ]);

        // Opsional: Membuat Akun Tamu (Jika perlu)
        /*
        User::create([
            'name' => 'Tamu Kantor',
            'email' => 'tamu@bpk.go.id',
            'password' => Hash::make('tamu123'),
            'role' => 'user',
        ]);
        */
    }
}