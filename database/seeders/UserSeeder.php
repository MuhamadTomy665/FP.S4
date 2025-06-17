<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // ✅ Tambahkan ini

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'pasien',
            'nik' => '3216255201105',
            'email' => 'pasien@gmail.com',
            'no_hp' => '081945891435',
            'role' => 'pasien',
            'status' => 'BPJS',
            'password' => bcrypt('user'), // ✅ Harus dienkripsi
        ]);

        User::create([
            'name' => 'admin',
            'nik' => '3216255201106',
            'email' => 'admin@gmail.com',
            'no_hp' => '081945891435',
            'role' => 'admin',
            'status' => 'BPJS',
            'password' => bcrypt('admin'),
        ]);

        User::create([
            'name' => 'petugas',
            'nik' => '3216255201107',
            'email' => 'petugas@gmail.com',
            'no_hp' => '081945891435',
            'role' => 'petugas',
            'status' => 'BPJS',
            'password' => bcrypt('petugas'),
        ]);
    }
}
