<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // List backup files
        $backupFiles = [];
        $backupPath = storage_path('app/backups');
        
        if (is_dir($backupPath)) {
            $files = glob($backupPath . '/*.sql');
            foreach ($files as $file) {
                $backupFiles[] = [
                    'name' => basename($file),
                    'size' => filesize($file),
                    'date' => date('Y-m-d H:i:s', filemtime($file)),
                ];
            }
            // Sort by date descending
            usort($backupFiles, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }

        return view('backup.index', compact('backupFiles'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            $backupPath = storage_path('app/backups');
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $filepath = $backupPath . '/' . $filename;

            // Get database config
            $dbConnection = config('database.default');
            $dbConfig = config("database.connections.{$dbConnection}");
            
            // Check if using SQLite
            if ($dbConfig['driver'] === 'sqlite') {
                // For SQLite, copy the database file directly
                $dbPath = database_path($dbConfig['database']);
                if (file_exists($dbPath)) {
                    copy($dbPath, $filepath);
                    return redirect()->route('backup.index')
                        ->with('success', 'Backup berhasil dibuat: ' . $filename);
                } else {
                    // Fallback: Export data secara manual
                    $this->createManualBackup($filepath);
                    return redirect()->route('backup.index')
                        ->with('success', 'Backup berhasil dibuat (manual): ' . $filename);
                }
            } else {
                // For MySQL/MariaDB, use mysqldump
                $dbName = $dbConfig['database'];
                $dbUser = $dbConfig['username'];
                $dbPass = $dbConfig['password'];
                $dbHost = $dbConfig['host'];

                // Create backup using mysqldump
                $command = sprintf(
                    'mysqldump --host=%s --user=%s --password=%s %s > %s',
                    escapeshellarg($dbHost),
                    escapeshellarg($dbUser),
                    escapeshellarg($dbPass),
                    escapeshellarg($dbName),
                    escapeshellarg($filepath)
                );

                exec($command, $output, $returnVar);

                if ($returnVar === 0 && file_exists($filepath)) {
                    return redirect()->route('backup.index')
                        ->with('success', 'Backup berhasil dibuat: ' . $filename);
                } else {
                    // Fallback: Export data secara manual
                    $this->createManualBackup($filepath);
                    return redirect()->route('backup.index')
                        ->with('success', 'Backup berhasil dibuat (manual): ' . $filename);
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    protected function createManualBackup($filepath)
    {
        $tables = ['users', 'siswa', 'pembayaran', 'settings', 'notifications', 'audit_logs'];
        $content = "-- Backup Database TKIT Jamilul Mu'minin\n";
        $content .= "-- Created: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $content .= "-- Table: {$table}\n";
                $rows = DB::table($table)->get();
                
                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array)$row as $key => $value) {
                        $values[] = is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                    }
                    $content .= "INSERT INTO `{$table}` VALUES (" . implode(', ', $values) . ");\n";
                }
                $content .= "\n";
            }
        }

        file_put_contents($filepath, $content);
    }

    public function download($filename)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $filepath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($filepath)) {
            abort(404);
        }

        return response()->download($filepath);
    }

    public function delete($filename)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $filepath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filepath)) {
            unlink($filepath);
            return redirect()->route('backup.index')
                ->with('success', 'Backup berhasil dihapus.');
        }

        return redirect()->route('backup.index')
            ->with('error', 'File backup tidak ditemukan.');
    }
}
