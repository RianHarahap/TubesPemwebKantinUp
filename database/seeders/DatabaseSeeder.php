<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        User::truncate(); 

        // Buat user dummy
        User::create([
            'name' => 'mahasiswa_up', // Ini yang akan diketik di kolom Username
            'email' => 'user@gmail.com',
            'password' => Hash::make('password123'), // Ini passwordnya
            'role' => 'user'
        ]);
        
        // Tambahkan admin untuk tes nanti
        User::create([
            'name' => 'admin_up',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Buat User Penjual (Pastikan baris ini ada!)
        \App\Models\User::create([
        'name' => 'penjual_up',
        'email' => 'penjual@gmail.com',
        'password' => bcrypt('password123'),
        'role' => 'penjual'
        ]);
    }
}