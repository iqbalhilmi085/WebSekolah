<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, date, etc
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'nominal_spp', 'value' => '200000', 'type' => 'number', 'description' => 'Nominal SPP per bulan (Rupiah)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tahun_ajaran', 'value' => date('Y') . '/' . (date('Y') + 1), 'type' => 'text', 'description' => 'Tahun Ajaran', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'nama_sekolah', 'value' => 'TKIT Jamilul Mu\'minin', 'type' => 'text', 'description' => 'Nama Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'alamat_sekolah', 'value' => 'Jl. Pendidikan No. 123, Makassar', 'type' => 'text', 'description' => 'Alamat Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'telepon_sekolah', 'value' => '0411-000-000', 'type' => 'text', 'description' => 'Telepon Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'no_rekening', 'value' => '123456789', 'type' => 'text', 'description' => 'Nomor Rekening Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'nama_bank', 'value' => 'Bank Syariah', 'type' => 'text', 'description' => 'Nama Bank', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'cp_tu', 'value' => '0812-0000-1234', 'type' => 'text', 'description' => 'Kontak Person TU (WhatsApp)', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
