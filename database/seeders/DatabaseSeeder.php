<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncate
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data lama jika ada
        User::truncate();
        \App\Models\Vendor::truncate();
        \App\Models\Menu::truncate();
        \App\Models\Order::truncate(); 

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
        $penjual = \App\Models\User::create([
        'name' => 'penjual_up',
        'email' => 'penjual@gmail.com',
        'password' => bcrypt('password123'),
        'role' => 'penjual',
        'vendor_id' => 1  // Assign to first vendor (Kantin Biru)
        ]);

        $kantins = [
            ['nama' => 'Kantin Biru', 'desc' => 'Spesialis Ayam Penyet & Sambal'],
            ['nama' => 'D’Geprek', 'desc' => 'Ayam Geprek Level Mahasiswa'],
            ['nama' => 'Kantin Sehat', 'desc' => 'Makanan Rumahan & Sayur Segar'],
            ['nama' => 'Kedai Kopi UP', 'desc' => 'Kopi dan Cemilan Tugas'],
        ];

        foreach ($kantins as $k) {
            $vendor = \App\Models\Vendor::create([
                'nama_kantin' => $k['nama'],
                'deskripsi' => $k['desc'],
                'is_open' => true,
            ]);

            // Buat 3 Menu per Kantin
            for ($i = 1; $i <= 3; $i++) {
                \App\Models\Menu::create([
                    'vendor_id' => $vendor->id,
                    'nama_makanan' => $k['nama'] . " Menu " . $i,
                    'harga' => rand(10000, 25000),
                    'deskripsi' => 'Deskripsi lezat untuk menu ini yang sangat menggugah selera.',
                    'tersedia' => true,
              ]);
            }
        }

        // Buat beberapa order untuk testing
        $statuses = ['menunggu', 'dimasak', 'siap', 'selesai'];
        $queueNumbers = ['A-001', 'A-002', 'B-001', 'B-002', 'C-001'];

        for ($i = 0; $i < 5; $i++) {
            $menu = \App\Models\Menu::where('vendor_id', 1)->first();
            \App\Models\Order::create([
                'user_id' => 1,  // mahasiswa_up
                'vendor_id' => 1,  // Kantin Biru (penjual_up)
                'menu_id' => $menu ? $menu->id : null,
                'menu_name' => $menu ? $menu->nama_makanan : 'Menu Sample',
                'jumlah' => rand(1, 3),
                'nomor_antrean' => $queueNumbers[$i],
                'total_harga' => rand(15000, 35000),
                'status' => $statuses[$i % count($statuses)],
                'estimasi_menit' => rand(10, 30),
            ]);
        }

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');    }
}