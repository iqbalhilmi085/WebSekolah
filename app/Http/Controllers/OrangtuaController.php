<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrangtuaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function ensureOrangtua(): void
    {
        if (!Auth::check() || !Auth::user()->isOrangtua()) {
            abort(403, 'Hanya orang tua yang dapat mengakses halaman ini.');
        }
    }

    public function dashboard()
    {
        $this->ensureOrangtua();

        // Ambil semua anak yang terhubung dengan nomor HP orang tua
        $anakList = Siswa::where('no_telp', Auth::user()->no_hp ?? null)
            ->orderBy('kelas')
            ->orderBy('nama')
            ->get();

        $ringkasan = null;
        $siswaUtama = $anakList->first();

        // Hitung ringkasan pembayaran tahun ini untuk anak pertama (jika ada)
        if ($siswaUtama) {
            $tahun = date('Y');
            $pembayaranTahunIni = $siswaUtama->pembayaran()
                ->where('tahun', $tahun)
                ->get();

            $ringkasan = [
                'tahun' => $tahun,
                'total_transaksi' => $pembayaranTahunIni->count(),
                'total_lunas' => $pembayaranTahunIni->where('status', 'lunas')->count(),
                'total_belum' => $pembayaranTahunIni->whereIn('status', ['belum_lunas', 'cicilan'])->count(),
            ];
        }

        return view('orangtua.dashboard', [
            'anakList' => $anakList,
            'siswaUtama' => $siswaUtama,
            'ringkasan' => $ringkasan,
        ]);
    }

    public function pembayaran(Request $request)
    {
        $this->ensureOrangtua();

        // Anak-anak yang terhubung dengan orang tua ini
        $anakList = Siswa::where('no_telp', Auth::user()->no_hp ?? null)
            ->where('status', 'aktif')
            ->orderBy('kelas')
            ->orderBy('nama')
            ->get();

        $selectedSiswaId = (int) ($request->get('siswa_id') ?: ($anakList->first()->id ?? 0));
        $tahun = (int) ($request->get('tahun') ?: date('Y'));
        $siswa = null;
        $riwayatPembayaran = collect();
        $bulanStatus = [];
        $tahunTersedia = [];
        $statusAkademik = null;

        if ($selectedSiswaId && $anakList->isNotEmpty()) {
            // Pastikan siswa yang dipilih memang milik orang tua ini
            $siswa = $anakList->firstWhere('id', $selectedSiswaId);

            if ($siswa) {
                // Ambil riwayat pembayaran untuk tahun yang dipilih
                // Urutkan berdasarkan bulan (Januari-Desember) lalu tanggal bayar terbaru
                $bulanOrder = [
                    'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                    'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                    'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
                ];
                
                // Ambil semua pembayaran untuk tahun yang dipilih
                $allPembayaran = $siswa->pembayaran()
                    ->where('tahun', $tahun)
                    ->get();
                
                // Urutkan: bulan (Januari-Desember), lalu tanggal bayar terbaru
                $riwayatPembayaran = $allPembayaran->sortBy(function($item) use ($bulanOrder) {
                    $bulan = $item->bulan ?? '';
                    $bulanNum = $bulanOrder[$bulan] ?? 99;
                    // Handle null tanggal_bayar dengan aman
                    if ($item->tanggal_bayar && $item->tanggal_bayar instanceof \Carbon\Carbon) {
                        $tanggalTimestamp = $item->tanggal_bayar->timestamp;
                    } else {
                        $tanggalTimestamp = 0;
                    }
                    // Sort by bulan ascending (1-12), then by tanggal descending (terbaru dulu)
                    return [$bulanNum, -$tanggalTimestamp];
                })->values();

                // Tahun-tahun yang punya transaksi
                $tahunTersedia = $siswa->pembayaran()
                    ->select('tahun')
                    ->distinct()
                    ->orderByDesc('tahun')
                    ->pluck('tahun')
                    ->toArray();

                // Status per bulan untuk tahun terpilih
                $bulanStatus = $this->buildMonthlyStatus($siswa, $tahun);

                // Status akademik (TK A / TK B / Lulus / Pindah)
                $statusAkademik = $this->getStatusAkademik($siswa);
            }
        }

        return view('orangtua.pembayaran', [
            'anakList' => $anakList,
            'selectedSiswaId' => $selectedSiswaId,
            'siswa' => $siswa,
            'riwayatPembayaran' => $riwayatPembayaran,
            'tahun' => $tahun,
            'tahunTersedia' => $tahunTersedia,
            'bulanStatus' => $bulanStatus,
            'statusAkademik' => $statusAkademik,
        ]);
    }

    public function uploadBukti(Request $request)
    {
        $this->ensureOrangtua();

        $validated = $request->validate([
            'siswa_id' => 'required|integer|exists:siswa,id',
            'bulan' => 'nullable|string|max:20',
            'tahun' => 'required|integer|min:2000',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Pastikan siswa tersebut memang milik orang tua ini
        $siswa = Siswa::where('id', $validated['siswa_id'])
            ->where('no_telp', Auth::user()->no_hp ?? null)
            ->firstOrFail();

        $data = [
            'siswa_id' => $siswa->id,
            'jenis_pembayaran' => 'SPP',
            'jumlah' => $validated['jumlah'],
            'tanggal_bayar' => now(),
            'bulan' => $validated['bulan'] ?? null,
            'tahun' => $validated['tahun'],
            'status' => 'belum_lunas',
            'status_verifikasi' => 'pending',
            'keterangan' => $validated['keterangan'] ?? 'Upload bukti transfer via portal orang tua',
        ];

        if ($request->hasFile('bukti_pembayaran')) {
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                ->store('bukti_pembayaran', 'public');
        }

        $pembayaran = Pembayaran::create($data);

        // Buat notifikasi untuk admin
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'pembayaran_verifikasi',
            'title' => 'Menunggu Verifikasi',
            'message' => "Bukti pembayaran SPP untuk {$siswa->nama} bulan {$validated['bulan']} telah dikirim dan menunggu verifikasi.",
            'notifiable_type' => Pembayaran::class,
            'notifiable_id' => $pembayaran->id,
        ]);

        return redirect()
            ->route('orangtua.pembayaran', ['siswa_id' => $siswa->id, 'tahun' => $validated['tahun']])
            ->with('success', 'Bukti pembayaran berhasil dikirim. Mohon tunggu verifikasi dari TU sekolah.');
    }

    /**
     * Bangun status pembayaran per bulan untuk satu tahun.
     */
    protected function buildMonthlyStatus(Siswa $siswa, int $tahun): array
    {
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April',
            'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember',
        ];

        $result = [];

        foreach ($bulanList as $bulan) {
            $p = $siswa->pembayaran()
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->orderByDesc('created_at')
                ->first();

            if ($p) {
                $status = $p->status;
            } else {
                $status = 'belum_ada'; // belum ada transaksi
            }

            $result[] = [
                'bulan' => $bulan,
                'status' => $status,
            ];
        }

        return $result;
    }

    /**
     * Tentukan label status akademik berdasarkan kelas & status siswa.
     */
    protected function getStatusAkademik(Siswa $siswa): string
    {
        $kelas = strtolower($siswa->kelas ?? '');

        if ($siswa->status === 'nonaktif') {
            if (str_contains($kelas, 'pindah')) {
                return 'Pindah Sekolah';
            }

            if (str_contains($kelas, 'tk b') || str_contains($kelas, 'tkb')) {
                return 'Lulus TK B';
            }

            if (str_contains($kelas, 'tk a') || str_contains($kelas, 'tka')) {
                return 'Lulus TK A';
            }

            return 'Tidak Aktif';
        }

        if (str_contains($kelas, 'tk a') || str_contains($kelas, 'tka')) {
            return 'Aktif TK A';
        }

        if (str_contains($kelas, 'tk b') || str_contains($kelas, 'tkb')) {
            return 'Aktif TK B';
        }

        return 'Aktif';
    }

    public function updateBukti(Request $request, $id)
    {
        $this->ensureOrangtua();

        $validated = $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Pastikan pembayaran tersebut milik anak dari orang tua ini
        $pembayaran = Pembayaran::with('siswa')->findOrFail($id);
        $siswa = $pembayaran->siswa;

        if (!$siswa || $siswa->no_telp !== Auth::user()->no_hp) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah bukti pembayaran ini');
        }

        // Hapus file lama jika ada
        if ($pembayaran->bukti_pembayaran) {
            try {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete old bukti_pembayaran: ' . $e->getMessage());
            }
        }

        // Upload file baru
        $newPath = $request->file('bukti_pembayaran')
            ->store('bukti_pembayaran', 'public');

        // Update pembayaran
        $pembayaran->update([
            'bukti_pembayaran' => $newPath,
            'status_verifikasi' => 'pending', // Reset status verifikasi karena bukti baru
        ]);

        // Buat notifikasi untuk admin
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'pembayaran_verifikasi',
            'title' => 'Bukti Pembayaran Diperbarui',
            'message' => "Bukti pembayaran SPP untuk {$siswa->nama} bulan {$pembayaran->bulan} telah diperbarui dan menunggu verifikasi ulang.",
            'notifiable_type' => Pembayaran::class,
            'notifiable_id' => $pembayaran->id,
        ]);

        return redirect()
            ->route('orangtua.pembayaran', ['siswa_id' => $siswa->id, 'tahun' => $pembayaran->tahun])
            ->with('success', 'Bukti pembayaran berhasil diperbarui. Mohon tunggu verifikasi dari TU sekolah.');
    }

    public function notifikasi()
    {
        $this->ensureOrangtua();

        $notifikasi = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Mark all as read
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('orangtua.notifikasi', compact('notifikasi'));
    }
}


