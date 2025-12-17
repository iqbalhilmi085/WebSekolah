<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder ini dikosongkan karena seluruh user dummy
        // sudah diatur di DatabaseSeeder agar konsisten:
        // - 1 admin
        // - 2 orang tua (untuk 2 siswa)
        //
        // Biarkan file ini ada supaya tidak error jika dipanggil,
        // tetapi tidak menambah data tambahan lagi.
    }
}
