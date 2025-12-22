<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function show()
    {
        return view('pendaftaran');
    }

    public function store(Request $request)
    {
        // TIDAK ADA BATASAN MAKSIMAL REGISTRASI
        // Sistem dapat menerima pendaftaran tanpa batas jumlah
        
        $validated = $request->validate([
            // Data Anak
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'kelas' => 'required|in:TK A,TK B',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Data Orang Tua
            'nama_orangtua' => 'required|string|max:255',
            'email' => 'required|email', // Validasi unique dilakukan manual di bawah
            'password' => 'required|min:6|confirmed',
            'no_telp' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // NIS akan diisi oleh admin nanti
            // Data akan disimpan ke database sekolahtk (bukan database dummy)

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('foto_siswa', 'public');
            }

            // Set status default aktif untuk pendaftaran baru
            $validated['status'] = 'aktif';

            // Simpan data siswa ke database sekolahtk
            // TIDAK ADA BATASAN JUMLAH - dapat menerima pendaftaran tanpa batas
            $siswa = Siswa::create([
                'nis' => null, // NIS akan diisi oleh admin
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'alamat' => $validated['alamat'],
                'nama_orangtua' => $validated['nama_orangtua'],
                'no_telp' => $validated['no_telp'],
                'kelas' => $validated['kelas'],
                'foto' => $validated['foto'] ?? null,
                'status' => $validated['status'],
            ]);

            // Cek apakah user dengan no_hp sudah ada (untuk kasus satu orang tua punya beberapa anak)
            $existingUser = User::where('no_hp', $validated['no_telp'])->where('role', 'orangtua')->first();
            
            if ($existingUser) {
                // Jika user sudah ada dengan nomor yang sama, gunakan user tersebut
                // Update email dan password jika berbeda
                if ($existingUser->email !== $validated['email']) {
                    // Cek apakah email baru sudah digunakan oleh user lain
                    $emailExists = User::where('email', $validated['email'])->where('id', '!=', $existingUser->id)->exists();
                    if ($emailExists) {
                        DB::rollBack();
                        return back()
                            ->withInput()
                            ->withErrors(['email' => 'Email sudah digunakan oleh akun lain.']);
                    }
                    $existingUser->update([
                        'email' => $validated['email'],
                        'password' => Hash::make($validated['password']),
                    ]);
                } else {
                    // Update password saja jika email sama
                    $existingUser->update([
                        'password' => Hash::make($validated['password']),
                    ]);
                }
                $user = $existingUser;
            } else {
                // Cek apakah email sudah digunakan
                $emailExists = User::where('email', $validated['email'])->exists();
                if ($emailExists) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->withErrors(['email' => 'Email sudah digunakan.']);
                }
                
                // Buat akun orang tua baru ke database sekolahtk
                // TIDAK ADA BATASAN JUMLAH - dapat menerima pendaftaran tanpa batas
                $user = User::create([
                    'name' => $validated['nama_orangtua'],
                    'email' => $validated['email'],
                    'no_hp' => $validated['no_telp'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'orangtua',
                ]);
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Pendaftaran berhasil! Data pendaftaran Anda sedang diproses. NIS akan diberikan oleh admin. Silakan login dengan email: ' . $validated['email']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PendaftaranController::store - Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi atau hubungi admin.']);
        }
    }
}
