<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
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

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tahun
        $tahun = $request->get('tahun', date('Y'));
        $query->where('tahun', $tahun);

        $pembayaran = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $kelasList = Siswa::where('status', 'aktif')->distinct()->pluck('kelas');
        
        return view('pembayaran.index', compact('pembayaran', 'siswaList', 'kelasList', 'tahun'));
    }

    public function create()
    {
        $siswa = Siswa::where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        
        return view('pembayaran.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'siswa_id' => 'required|exists:siswa,id',
                'jenis_pembayaran' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:0',
                'tanggal_bayar' => 'nullable|date',
                'bulan' => 'nullable|string|max:20',
                'tahun' => 'required|integer|min:2000|max:2100',
                'status' => 'required|in:lunas,belum_lunas,cicilan',
                'keterangan' => 'nullable|string',
                'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('bukti_pembayaran')) {
                $validated['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                    ->store('bukti_pembayaran', 'public');
            }

            Pembayaran::create($validated);

            return redirect()->route('pembayaran.index')
                ->with('success', 'Data pembayaran berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('PembayaranController::store - Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with('siswa')->findOrFail($id);
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $siswa = Siswa::where('status', 'aktif')
            ->orderBy('nama')
            ->get();
        
        return view('pembayaran.edit', compact('pembayaran', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        try {
            $pembayaran = Pembayaran::findOrFail($id);

            $validated = $request->validate([
                'siswa_id' => 'required|exists:siswa,id',
                'jenis_pembayaran' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:0',
                'tanggal_bayar' => 'nullable|date',
                'bulan' => 'nullable|string|max:20',
                'tahun' => 'required|integer|min:2000|max:2100',
                'status' => 'required|in:lunas,belum_lunas,cicilan',
                'keterangan' => 'nullable|string',
                'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('bukti_pembayaran')) {
                if ($pembayaran->bukti_pembayaran) {
                    try {
                        Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old bukti_pembayaran: ' . $e->getMessage());
                    }
                }
                $validated['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                    ->store('bukti_pembayaran', 'public');
            }

            $pembayaran->update($validated);

            return redirect()->route('pembayaran.index')
                ->with('success', 'Data pembayaran berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('PembayaranController::update - Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data.'])->withInput();
        }
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        if ($pembayaran->bukti_pembayaran) {
            Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        }
        
        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus!');
    }

    public function dashboardStatus()
    {
        try {
            $siswa = Siswa::with(['pembayaran' => function($query) {
                $query->where('tahun', date('Y'))
                    ->orderBy('created_at', 'desc');
            }])
            ->where('status', 'aktif')
            ->orderBy('kelas')
            ->orderBy('nama')
            ->get();

            $statistik = [
                'total_siswa' => $siswa->count(),
                'lunas' => $siswa->filter(function($s) {
                    return $s->pembayaran->where('status', 'lunas')->count() > 0;
                })->count(),
                'belum_lunas' => $siswa->filter(function($s) {
                    return $s->pembayaran->where('status', 'belum_lunas')->count() > 0;
                })->count(),
            ];

            return view('dashboardStatusPembayaran', compact('siswa', 'statistik'));
        } catch (\Exception $e) {
            \Log::error('PembayaranController::dashboardStatus - Error: ' . $e->getMessage());
            return view('dashboardStatusPembayaran', [
                'siswa' => collect(),
                'statistik' => [
                    'total_siswa' => 0,
                    'lunas' => 0,
                    'belum_lunas' => 0,
                ]
            ])->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function kwitansi($id)
    {
        $pembayaran = Pembayaran::with('siswa')->findOrFail($id);
        
        // Untuk sementara return view, nanti bisa diganti dengan PDF
        return view('pembayaran.kwitansi', compact('pembayaran'));
    }

    public function printKwitansi($id)
    {
        $pembayaran = Pembayaran::with('siswa')->findOrFail($id);
        return view('pembayaran.kwitansi-print', compact('pembayaran'));
    }

    public function downloadKwitansi($id)
    {
        $pembayaran = Pembayaran::with('siswa')->findOrFail($id);
        
        $pdf = Pdf::loadView('pembayaran.kwitansi-pdf', compact('pembayaran'));
        
        return $pdf->download('kwitansi-' . $pembayaran->siswa->nis . '-' . date('Ymd') . '.pdf');
    }
}