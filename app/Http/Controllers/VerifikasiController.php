<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Notification;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
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

        $query = Pembayaran::with(['siswa', 'verifier'])
            ->whereNotNull('bukti_pembayaran');

        // Filter status verifikasi
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        } else {
            $query->where('status_verifikasi', 'pending');
        }

        $pembayaran = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('verifikasi.index', compact('pembayaran'));
    }

    public function approve(Request $request, $id)
    {
        try {
            if (!Auth::user()->isAdmin()) {
                abort(403);
            }

            $pembayaran = Pembayaran::with('siswa')->findOrFail($id);
            
            $pembayaran->update([
                'status_verifikasi' => 'diterima',
                'status' => 'lunas',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Audit log
            try {
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'approve',
                    'model_type' => Pembayaran::class,
                    'model_id' => $pembayaran->id,
                    'description' => "Pembayaran SPP untuk {$pembayaran->siswa->nama} bulan {$pembayaran->bulan} telah diterima",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to create audit log: ' . $e->getMessage());
            }

            // Buat notifikasi untuk orang tua
            $user = User::where('no_hp', $pembayaran->siswa->no_telp)->first();
            if ($user) {
                try {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'pembayaran_diterima',
                        'title' => 'Pembayaran Diterima',
                        'message' => "Pembayaran SPP untuk {$pembayaran->siswa->nama} bulan {$pembayaran->bulan} telah diterima dan diverifikasi.",
                        'notifiable_type' => Pembayaran::class,
                        'notifiable_id' => $pembayaran->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Failed to create notification: ' . $e->getMessage());
                }
            }

            return redirect()->route('verifikasi.index')
                ->with('success', 'Pembayaran berhasil diverifikasi dan diterima!');
        } catch (\Exception $e) {
            \Log::error('VerifikasiController::approve - Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memverifikasi pembayaran.']);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            if (!Auth::user()->isAdmin()) {
                abort(403);
            }

            $validated = $request->validate([
                'alasan_penolakan' => 'required|string|min:10',
            ]);

            $pembayaran = Pembayaran::with('siswa')->findOrFail($id);
            
            $pembayaran->update([
                'status_verifikasi' => 'ditolak',
                'alasan_penolakan' => $validated['alasan_penolakan'],
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Audit log
            try {
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'reject',
                    'model_type' => Pembayaran::class,
                    'model_id' => $pembayaran->id,
                    'description' => "Pembayaran SPP untuk {$pembayaran->siswa->nama} bulan {$pembayaran->bulan} ditolak. Alasan: {$validated['alasan_penolakan']}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to create audit log: ' . $e->getMessage());
            }

            // Buat notifikasi untuk orang tua
            $user = User::where('no_hp', $pembayaran->siswa->no_telp)->first();
            if ($user) {
                try {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'pembayaran_ditolak',
                        'title' => 'Pembayaran Ditolak',
                        'message' => "Pembayaran SPP untuk {$pembayaran->siswa->nama} bulan {$pembayaran->bulan} ditolak. Alasan: {$validated['alasan_penolakan']}",
                        'notifiable_type' => Pembayaran::class,
                        'notifiable_id' => $pembayaran->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Failed to create notification: ' . $e->getMessage());
                }
            }

            return redirect()->route('verifikasi.index')
                ->with('success', 'Pembayaran ditolak dan notifikasi telah dikirim ke orang tua.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('VerifikasiController::reject - Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menolak pembayaran.']);
        }
    }
}
