<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['pending', 'diterima', 'ditolak'])->default('pending')->after('status');
            $table->text('alasan_penolakan')->nullable()->after('status_verifikasi');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null')->after('alasan_penolakan');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['status_verifikasi', 'alasan_penolakan', 'verified_by', 'verified_at']);
        });
    }
};
