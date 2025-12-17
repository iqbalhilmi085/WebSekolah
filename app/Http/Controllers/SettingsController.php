<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
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

        $settings = Setting::all()->keyBy('key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nominal_spp' => 'required|numeric|min:0',
            'tahun_ajaran' => 'required|string',
            'nama_sekolah' => 'required|string',
            'alamat_sekolah' => 'required|string',
            'telepon_sekolah' => 'required|string',
            'no_rekening' => 'required|string',
            'nama_bank' => 'required|string',
            'cp_tu' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
