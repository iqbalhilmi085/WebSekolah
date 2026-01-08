<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
    /**
     * Serve storage files dengan authorization
     */
    public function serve($path)
    {
        try {
            // Decode path jika ada encoding (bisa multiple encoding)
            $originalPath = $path;
            $path = urldecode($path);
            // Decode sekali lagi jika masih ada encoding
            if ($path !== urldecode($path)) {
                $path = urldecode($path);
            }
            
            // Normalize path - remove leading/trailing slashes
            $path = trim($path, '/');
            
            // Check if path is empty
            if (empty($path)) {
                Log::warning('StorageController: Empty path', ['original' => $originalPath]);
                abort(404, 'File path is empty');
            }
            
            // Authorization check untuk bukti pembayaran (harus login)
            // File lain seperti logo, foto siswa, dll bisa diakses tanpa login
            if (strpos($path, 'bukti_pembayaran') !== false) {
                $this->authorizeBuktiPembayaran($path);
            }
            
            // Get file path - coba beberapa lokasi
            $filePath = null;
            $possiblePaths = [
                Storage::disk('public')->path($path),
                storage_path('app/public/' . $path),
                public_path('storage/' . $path),
            ];
            
            foreach ($possiblePaths as $possiblePath) {
                if (file_exists($possiblePath)) {
                    $filePath = $possiblePath;
                    break;
                }
            }
            
            // Jika belum ditemukan, cek menggunakan Storage facade
            if (!$filePath) {
                if (Storage::disk('public')->exists($path)) {
                    $filePath = Storage::disk('public')->path($path);
                } else {
                    Log::warning('StorageController: File not found', [
                        'path' => $path,
                        'tried_paths' => $possiblePaths,
                        'storage_exists' => Storage::disk('public')->exists($path),
                        'user_id' => Auth::id()
                    ]);
                    abort(404, 'File not found: ' . $path);
                }
            }

            // Get file mime type
            $mimeType = mime_content_type($filePath);
            
            if (!$mimeType || $mimeType === 'application/octet-stream') {
                // Fallback mime type berdasarkan extension
                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $mimeTypes = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'pdf' => 'application/pdf',
                    'webp' => 'image/webp',
                ];
                $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
            }
            
            // Cek apakah request untuk download atau view
            $isDownload = request()->query('download') === '1' || request()->query('download') === 'true' || request()->has('download');
            
            // Get filename untuk Content-Disposition
            $filename = basename($path);
            // Escape filename untuk header
            $filename = str_replace(['"', "\n", "\r"], '', $filename);
            
            // Jika download, gunakan response()->download() untuk memastikan file terdownload
            if ($isDownload) {
                return response()->download($filePath, $filename, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=3600',
                    'Access-Control-Allow-Origin' => '*',
                ]);
            }
            
            // Jika view, gunakan response()->file() untuk menampilkan di browser
            $response = response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=3600',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
            
            // Log sukses untuk debugging
            Log::info('StorageController: File served successfully', [
                'path' => $path,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'user_id' => Auth::id()
            ]);
            
            return $response;
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            Log::error('StorageController: FileNotFoundException', [
                'path' => $path ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            abort(404, 'File not found');
        } catch (\Exception $e) {
            Log::error('StorageController: Error serving file', [
                'path' => $path ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'File not found: ' . ($e->getMessage() ?? 'Unknown error'));
        }
    }

    /**
     * Authorize access to bukti pembayaran
     * Admin dapat melihat semua, orang tua hanya dapat melihat bukti pembayaran anaknya
     */
    protected function authorizeBuktiPembayaran($path)
    {
        // Jika tidak ada user yang login, tolak akses
        if (!Auth::check()) {
            Log::warning('StorageController: No authenticated user', ['path' => $path]);
            abort(403, 'Unauthorized access');
        }

        $user = Auth::user();

        // Admin dapat melihat semua file
        if ($user->isAdmin()) {
            return;
        }

        // Normalisasi path untuk pencocokan
        $normalizedPath = str_replace('\\', '/', $path);
        $normalizedPath = ltrim($normalizedPath, '/');
        $filename = basename($normalizedPath);

        // Log untuk debugging
        Log::info('StorageController: Authorizing bukti pembayaran', [
            'path' => $path,
            'normalized_path' => $normalizedPath,
            'filename' => $filename,
            'user_id' => $user->id
        ]);

        // Untuk orang tua, cek apakah file ini milik anak mereka
        // Cari pembayaran yang menggunakan file ini
        // Coba beberapa variasi path yang mungkin (exact match, dengan/ tanpa leading slash, dll)
        $pembayaran = Pembayaran::where(function($query) use ($normalizedPath, $filename, $path) {
                // Exact match dengan berbagai variasi
                $query->where('bukti_pembayaran', $normalizedPath)
                      ->orWhere('bukti_pembayaran', $path)
                      ->orWhere('bukti_pembayaran', '/' . $normalizedPath)
                      ->orWhere('bukti_pembayaran', ltrim($path, '/'))
                      // Match by filename (paling reliable) - escape untuk LIKE
                      ->orWhere('bukti_pembayaran', 'like', '%' . str_replace(['%', '_'], ['\%', '\_'], $filename))
                      ->orWhere('bukti_pembayaran', 'like', 'bukti_pembayaran/' . str_replace(['%', '_'], ['\%', '\_'], $filename))
                      ->orWhere('bukti_pembayaran', 'like', '%/' . str_replace(['%', '_'], ['\%', '\_'], $filename))
                      ->orWhere('bukti_pembayaran', 'like', '%\\' . str_replace(['%', '_'], ['\%', '\_'], $filename)); // Windows path
            })
            ->with('siswa')
            ->first();

        // Jika tidak ditemukan dengan exact match, coba cari berdasarkan filename saja
        if (!$pembayaran) {
            $allPayments = Pembayaran::whereNotNull('bukti_pembayaran')
                ->where('bukti_pembayaran', 'like', '%' . str_replace(['%', '_'], ['\%', '\_'], $filename))
                ->with('siswa')
                ->get();
            
            $pembayaran = $allPayments->first(function($p) use ($filename) {
                // Cek apakah filename cocok dengan path di database
                $dbPath = $p->bukti_pembayaran;
                $dbFilename = basename(str_replace('\\', '/', $dbPath));
                return $dbFilename === $filename;
            });
        }

        if (!$pembayaran) {
            // Jika tidak ditemukan di database, mungkin file tidak valid
            // Untuk keamanan, tolak akses kecuali admin
            Log::warning('StorageController: Payment not found for file', [
                'path' => $path,
                'normalized_path' => $normalizedPath,
                'filename' => $filename,
                'user_id' => $user->id,
                'user_role' => $user->role ?? 'unknown'
            ]);
            abort(403, 'File tidak ditemukan atau tidak memiliki akses');
        }

        // Jika user adalah orang tua, pastikan siswa tersebut adalah anak mereka
        if ($user->isOrangtua()) {
            $siswa = $pembayaran->siswa;
            if (!$siswa) {
                Log::warning('StorageController: Student not found for payment', [
                    'pembayaran_id' => $pembayaran->id,
                    'user_id' => $user->id
                ]);
                abort(403, 'Data siswa tidak ditemukan');
            }
            
            if ($siswa->no_telp !== $user->no_hp) {
                Log::warning('StorageController: Unauthorized access attempt', [
                    'user_id' => $user->id,
                    'user_no_hp' => $user->no_hp,
                    'siswa_id' => $siswa->id,
                    'siswa_no_telp' => $siswa->no_telp,
                    'pembayaran_id' => $pembayaran->id
                ]);
                abort(403, 'Anda tidak memiliki akses untuk melihat bukti pembayaran ini');
            }
            
            // Authorization berhasil
            Log::info('StorageController: Authorization successful', [
                'pembayaran_id' => $pembayaran->id,
                'user_id' => $user->id,
                'path' => $path
            ]);
            return;
        }

        // Role lain tidak memiliki akses
        Log::warning('StorageController: Unauthorized role', [
            'user_id' => $user->id,
            'user_role' => $user->role ?? 'unknown',
            'path' => $path
        ]);
        abort(403, 'Unauthorized access');
    }
}
