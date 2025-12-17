<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageController extends Controller
{
    /**
     * Serve storage files
     */
    public function serve($path)
    {
        try {
            // Decode path jika ada encoding
            $path = urldecode($path);
            
            // Normalize path - remove leading/trailing slashes
            $path = trim($path, '/');
            
            // Check if path is empty
            if (empty($path)) {
                abort(404, 'File path is empty');
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
            
            if (!$filePath) {
                // Check if file exists in storage using Storage facade
                if (!Storage::disk('public')->exists($path)) {
                    Log::warning('StorageController: File not found', [
                        'path' => $path,
                        'tried_paths' => $possiblePaths
                    ]);
                    abort(404, 'File not found: ' . $path);
                }
                // If Storage says it exists but file not found, use Storage path
                $filePath = Storage::disk('public')->path($path);
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
            
            // Return file response
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=3600',
                'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            ]);
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
}
