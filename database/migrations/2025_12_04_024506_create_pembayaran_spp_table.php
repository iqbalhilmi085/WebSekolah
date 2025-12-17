<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->string('jenis_pembayaran'); // SPP, Uang Pangkal, Seragam, dll
            $table->decimal('jumlah', 10, 2);
            $table->date('tanggal_bayar');
            $table->string('bulan')->nullable(); // Untuk SPP
            $table->integer('tahun');
            $table->enum('status', ['lunas', 'belum_lunas', 'cicilan'])->default('belum_lunas');
            $table->text('keterangan')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};