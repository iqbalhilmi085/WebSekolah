<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama (optional, untuk development/demo)
        // Nonaktifkan foreign key checks sementara untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('pembayaran')->truncate();
        DB::table('siswa')->truncate();
        DB::table('users')->truncate();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@tkit.com',
            'no_hp' => '081200000001',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Orang Tua & Siswa Dummy (hanya 2 siswa)

        // Orang Tua 1
        $ortu1 = User::create([
            'name' => 'Orang Tua Ahmad',
            'email' => 'ortu.ahmad@tkit.com',
            'no_hp' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'orangtua',
        ]);

        // Siswa 1
        $siswa1 = Siswa::create([
            'nis' => '0000001',
            'nama' => 'Ahmad Fadli',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2019-05-15',
            'alamat' => 'Jl. Melati No. 10, Makassar',
            'nama_orangtua' => 'Orang Tua Ahmad',
            'no_telp' => $ortu1->no_hp,
            'kelas' => 'TK A',
            'status' => 'aktif',
        ]);

        // Orang Tua 2
        $ortu2 = User::create([
            'name' => 'Orang Tua Siti',
            'email' => 'ortu.siti@tkit.com',
            'no_hp' => '081234567891',
            'password' => Hash::make('password123'),
            'role' => 'orangtua',
        ]);

        // Siswa 2
        $siswa2 = Siswa::create([
            'nis' => '0000002',
            'nama' => 'Siti Aisyah',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2019-03-20',
            'alamat' => 'Jl. Mawar No. 5, Makassar',
            'nama_orangtua' => 'Orang Tua Siti',
            'no_telp' => $ortu2->no_hp,
            'kelas' => 'TK B',
            'status' => 'aktif',
        ]);

        // 3. Tagihan / Pembayaran SPP Dummy
        $tahun = date('Y');
        $nominalSpp = 250000;

        // Siswa 1: 6 bulan sudah lunas, 6 bulan belum
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $status = $bulan <= 6 ? 'lunas' : 'belum_lunas';

            Pembayaran::create([
                'siswa_id' => $siswa1->id,
                'jenis_pembayaran' => 'SPP',
                'jumlah' => $nominalSpp,
                'tanggal_bayar' => $bulan <= 6
                    ? $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-05'
                    : null,
                'bulan' => date('F', mktime(0, 0, 0, $bulan, 1)),
                'tahun' => $tahun,
                'status' => $status,
                'status_verifikasi' => 'diterima',
                'keterangan' => $status === 'lunas'
                    ? 'Pembayaran SPP ' . date('F', mktime(0, 0, 0, $bulan, 1))
                    : 'Tagihan SPP ' . date('F', mktime(0, 0, 0, $bulan, 1)),
            ]);
        }

        // Siswa 2: 3 bulan sudah lunas, 9 bulan belum
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $status = $bulan <= 3 ? 'lunas' : 'belum_lunas';

            Pembayaran::create([
                'siswa_id' => $siswa2->id,
                'jenis_pembayaran' => 'SPP',
                'jumlah' => $nominalSpp,
                'tanggal_bayar' => $bulan <= 3
                    ? $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-07'
                    : null,
                'bulan' => date('F', mktime(0, 0, 0, $bulan, 1)),
                'tahun' => $tahun,
                'status' => $status,
                'status_verifikasi' => 'diterima',
                'keterangan' => $status === 'lunas'
                    ? 'Pembayaran SPP ' . date('F', mktime(0, 0, 0, $bulan, 1))
                    : 'Tagihan SPP ' . date('F', mktime(0, 0, 0, $bulan, 1)),
            ]);
        }

        $this->command?->info('Database seeded dengan data minimal: 1 admin, 2 siswa, 2 orang tua.');
        $this->command?->info('Admin - Email: admin@tkit.com, Password: admin123');
        $this->command?->info('Orang Tua 1 - Email: ortu.ahmad@tkit.com, Password: password123');
        $this->command?->info('Orang Tua 2 - Email: ortu.siti@tkit.com, Password: password123');
    }
}