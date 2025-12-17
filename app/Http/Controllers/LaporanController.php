<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = Pembayaran::with('siswa');

        // Filter bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter siswa
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        // Filter tahun
        $tahun = $request->get('tahun', date('Y'));
        $query->where('tahun', $tahun);

        $pembayaran = $query->orderBy('created_at', 'desc')->get();

        $statistik = [
            'total_transaksi' => $pembayaran->count(),
            'total_jumlah' => $pembayaran->sum('jumlah') ?? 0,
            'lunas' => $pembayaran->where('status', 'lunas')->count(),
            'belum_lunas' => $pembayaran->where('status', 'belum_lunas')->count(),
        ];

        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $kelasList = Siswa::where('status', 'aktif')->distinct()->pluck('kelas');

        return view('laporan.index', compact('pembayaran', 'statistik', 'siswaList', 'kelasList', 'tahun'));
    }

    public function exportPdf(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = Pembayaran::with('siswa');

        // Filter bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter siswa
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        // Filter tahun
        $tahun = $request->get('tahun', date('Y'));
        $query->where('tahun', $tahun);

        $pembayaran = $query->orderBy('created_at', 'desc')->get();

        $statistik = [
            'total_transaksi' => $pembayaran->count(),
            'total_jumlah' => $pembayaran->sum('jumlah') ?? 0,
            'lunas' => $pembayaran->where('status', 'lunas')->count(),
            'belum_lunas' => $pembayaran->where('status', 'belum_lunas')->count(),
        ];

        $settings = Setting::all()->keyBy('key');
        
        $pdf = Pdf::loadView('laporan.pdf', compact('pembayaran', 'statistik', 'tahun', 'settings'));
        
        $filename = 'laporan-pembayaran-' . $tahun;
        if ($request->filled('bulan')) {
            $filename .= '-' . strtolower($request->bulan);
        }
        $filename .= '-' . date('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = Pembayaran::with('siswa');

        // Filter bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter siswa
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        // Filter tahun
        $tahun = $request->get('tahun', date('Y'));
        $query->where('tahun', $tahun);

        $pembayaran = $query->orderBy('created_at', 'desc')->get();

        $filename = 'laporan-pembayaran-' . $tahun;
        if ($request->filled('bulan')) {
            $filename .= '-' . strtolower($request->bulan);
        }
        $filename .= '-' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pembayaran) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8 (agar Excel membaca dengan benar)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'Tanggal', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis', 'Bulan', 'Tahun', 'Jumlah', 'Status']);
            
            // Data
            $no = 1;
            foreach ($pembayaran as $item) {
                fputcsv($file, [
                    $no++,
                    ($item->tanggal_bayar && $item->tanggal_bayar instanceof \Carbon\Carbon) ? $item->tanggal_bayar->format('d/m/Y') : '-',
                    $item->siswa->nis ?? '-',
                    $item->siswa->nama ?? '-',
                    $item->siswa->kelas ?? '-',
                    $item->jenis_pembayaran ?? '-',
                    $item->bulan ?? '-',
                    $item->tahun ?? '-',
                    number_format($item->jumlah ?? 0, 0, ',', '.'),
                    strtoupper($item->status ?? '-'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
