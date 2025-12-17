<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function() {
    return view('index');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Password Reset Routes (Public - untuk Orang Tua saja)
Route::get('/password/forgot', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/password/forgot', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

// Public Pendaftaran (allow guests)
Route::get('/pendaftaran', [PendaftaranController::class, 'show'])->name('pendaftaran');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

// Public Storage Files (untuk bukti pembayaran, foto siswa, dll)
Route::get('/storage/{path}', [StorageController::class, 'serve'])
    ->where('path', '.*')
    ->name('storage.serve');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');

    // Siswa Management
    Route::resource('siswa', SiswaController::class);

    // Pembayaran Management
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/password', [AuthController::class, 'showChangePassword'])->name('password.form');
    Route::post('/password', [AuthController::class, 'changePassword'])->name('password.change');

    // Dashboard Status Pembayaran
    Route::get('/dashboard-status-pembayaran', [PembayaranController::class, 'dashboardStatus'])->name('dashboard.status.pembayaran');

    // Kwitansi Pembayaran
    Route::get('/pembayaran/{id}/kwitansi', [PembayaranController::class, 'kwitansi'])->name('pembayaran.kwitansi');
    Route::get('/pembayaran/{id}/print', [PembayaranController::class, 'printKwitansi'])->name('pembayaran.print');
    Route::get('/pembayaran/{id}/download', [PembayaranController::class, 'downloadKwitansi'])->name('pembayaran.download');

    // Verifikasi Pembayaran Cashless
    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::post('/verifikasi/{id}/approve', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');
    Route::post('/verifikasi/{id}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');

    // Laporan Pembayaran
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');

    // Pengaturan Sistem
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Portal Orang Tua
    Route::get('/orangtua/dashboard', [OrangtuaController::class, 'dashboard'])->name('orangtua.dashboard');
    Route::get('/orangtua/pembayaran', [OrangtuaController::class, 'pembayaran'])->name('orangtua.pembayaran');
    Route::post('/orangtua/pembayaran/upload-bukti', [OrangtuaController::class, 'uploadBukti'])->name('orangtua.pembayaran.upload-bukti');
    Route::get('/orangtua/notifikasi', [OrangtuaController::class, 'notifikasi'])->name('orangtua.notifikasi');

    // Backup Data Sistem
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/{filename}/download', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}', [BackupController::class, 'delete'])->name('backup.delete');

    // Audit Log
    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
});