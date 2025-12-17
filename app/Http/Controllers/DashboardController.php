<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $totalSiswa = Siswa::where('status', 'aktif')->count();
            $totalSiswaAktif = Siswa::where('status', 'aktif')->count();
            $totalSiswaNonaktif = Siswa::where('status', 'nonaktif')->count();
            
            // Handle null tanggal_bayar dengan aman
            $totalPembayaranBulanIni = Pembayaran::whereNotNull('tanggal_bayar')
                ->whereMonth('tanggal_bayar', date('m'))
                ->whereYear('tanggal_bayar', date('Y'))
                ->sum('jumlah') ?? 0;
            
            $pembayaranLunas = Pembayaran::lunas()
                ->tahunIni()
                ->count();
            
            $pembayaranBelumLunas = Pembayaran::belumLunas()
                ->tahunIni()
                ->count();
            
            // Grafik pembayaran per bulan - handle null tanggal_bayar
            $pembayaranPerBulan = Pembayaran::selectRaw(
                'MONTH(tanggal_bayar) as bulan, SUM(jumlah) as total'
            )
            ->whereNotNull('tanggal_bayar')
            ->whereYear('tanggal_bayar', date('Y'))
            ->groupBy(DB::raw('MONTH(tanggal_bayar)'))
            ->orderBy('bulan', 'asc')
            ->get();
            
            // Siswa terbaru
            $siswaTerbaru = Siswa::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // Pembayaran terbaru
            $pembayaranTerbaru = Pembayaran::with('siswa')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('dashboardAdmin', compact(
                'totalSiswa',
                'totalSiswaAktif',
                'totalSiswaNonaktif',
                'totalPembayaranBulanIni',
                'pembayaranLunas',
                'pembayaranBelumLunas',
                'pembayaranPerBulan',
                'siswaTerbaru',
                'pembayaranTerbaru'
            ));
        } catch (\Exception $e) {
            \Log::error('DashboardController::index - Error: ' . $e->getMessage());
            return view('dashboardAdmin', [
                'totalSiswa' => 0,
                'totalSiswaAktif' => 0,
                'totalSiswaNonaktif' => 0,
                'totalPembayaranBulanIni' => 0,
                'pembayaranLunas' => 0,
                'pembayaranBelumLunas' => 0,
                'pembayaranPerBulan' => collect(),
                'siswaTerbaru' => collect(),
                'pembayaranTerbaru' => collect(),
            ])->with('error', 'Terjadi kesalahan saat memuat data dashboard.');
        }
    }
}