<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%')
                  ->orWhere('kelas', 'like', '%' . $search . '%');
            });
        }

        $siswa = $query->orderBy('kelas')
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString(); // Preserve search parameter in pagination links
        
        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nis' => 'nullable|unique:siswa,nis|max:7|regex:/^[0-9]+$/',
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'nama_orangtua' => 'required|string|max:255',
                'no_telp' => 'required|string|max:20',
                'kelas' => 'required|string|max:50',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|in:aktif,nonaktif',
            ]);

            // Generate NIS otomatis jika tidak diisi (maksimal 7 angka)
            if (empty($validated['nis'])) {
                $lastSiswa = Siswa::whereNotNull('nis')
                    ->whereRaw('LENGTH(nis) <= 7')
                    ->orderByRaw('CAST(nis AS UNSIGNED) DESC')
                    ->first();
                
                if ($lastSiswa && is_numeric($lastSiswa->nis)) {
                    $newNumber = intval($lastSiswa->nis) + 1;
                } else {
                    $newNumber = 1;
                }
                
                // Pastikan maksimal 7 angka
                if ($newNumber > 9999999) {
                    $newNumber = 9999999; // Maksimal 7 digit
                }
                
                $validated['nis'] = str_pad($newNumber, 7, '0', STR_PAD_LEFT);
                
                // Cek apakah NIS sudah ada, jika ya increment lagi
                while (Siswa::where('nis', $validated['nis'])->exists()) {
                    $newNumber++;
                    if ($newNumber > 9999999) {
                        return back()->withErrors(['nis' => 'NIS sudah mencapai batas maksimal. Silakan input manual.'])->withInput();
                    }
                    $validated['nis'] = str_pad($newNumber, 7, '0', STR_PAD_LEFT);
                }
            }

            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('foto_siswa', 'public');
            }

            $siswa = Siswa::create($validated);

            // Generate akun orang tua otomatis jika belum ada
            $user = User::where('no_hp', $validated['no_telp'])->first();
            if (!$user) {
                try {
                    $defaultPassword = Hash::make('password123'); // Password default, bisa diganti
                    $user = User::create([
                        'name' => $validated['nama_orangtua'],
                        'email' => strtolower(str_replace(' ', '', $validated['nama_orangtua'])) . '@tkit.local',
                        'no_hp' => $validated['no_telp'],
                        'password' => $defaultPassword,
                        'role' => 'orangtua',
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Failed to create user for siswa: ' . $e->getMessage());
                }
            }

            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan! Akun orang tua: ' . ($user->email ?? 'Sudah ada') . ' (Password: password123)');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('SiswaController::store - Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data siswa.'])->withInput();
        }
    }

    public function show($id)
    {
        $siswa = Siswa::with('pembayaran')->findOrFail($id);
        return view('siswa.show', compact('siswa'));
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);

            $validated = $request->validate([
                'nis' => 'nullable|unique:siswa,nis,' . $id . '|max:7|regex:/^[0-9]+$/',
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'nama_orangtua' => 'required|string|max:255',
                'no_telp' => 'required|string|max:20',
                'kelas' => 'required|string|max:50',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|in:aktif,nonaktif',
            ]);

            // Generate NIS otomatis jika tidak diisi dan siswa belum punya NIS (maksimal 7 angka)
            if (empty($validated['nis']) && empty($siswa->nis)) {
                $lastSiswa = Siswa::whereNotNull('nis')
                    ->where('id', '!=', $id)
                    ->whereRaw('LENGTH(nis) <= 7')
                    ->orderByRaw('CAST(nis AS UNSIGNED) DESC')
                    ->first();
                
                if ($lastSiswa && is_numeric($lastSiswa->nis)) {
                    $newNumber = intval($lastSiswa->nis) + 1;
                } else {
                    $newNumber = 1;
                }
                
                // Pastikan maksimal 7 angka
                if ($newNumber > 9999999) {
                    $newNumber = 9999999; // Maksimal 7 digit
                }
                
                $validated['nis'] = str_pad($newNumber, 7, '0', STR_PAD_LEFT);
                
                // Cek apakah NIS sudah ada, jika ya increment lagi
                while (Siswa::where('nis', $validated['nis'])->where('id', '!=', $id)->exists()) {
                    $newNumber++;
                    if ($newNumber > 9999999) {
                        return back()->withErrors(['nis' => 'NIS sudah mencapai batas maksimal. Silakan input manual.'])->withInput();
                    }
                    $validated['nis'] = str_pad($newNumber, 7, '0', STR_PAD_LEFT);
                }
            } elseif (empty($validated['nis']) && !empty($siswa->nis)) {
                // Jika NIS dikosongkan tapi siswa sudah punya NIS, tetap gunakan NIS lama
                unset($validated['nis']);
            }

            if ($request->hasFile('foto')) {
                if ($siswa->foto) {
                    try {
                        Storage::disk('public')->delete($siswa->foto);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old foto: ' . $e->getMessage());
                    }
                }
                $validated['foto'] = $request->file('foto')->store('foto_siswa', 'public');
            }

            $siswa->update($validated);

            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('SiswaController::update - Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data siswa.'])->withInput();
        }
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }
        
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus!');
    }
}