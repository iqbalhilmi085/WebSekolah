<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.admin');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $v = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $login = $request->input('email');
        $password = $request->input('password');

        // Determine whether input is an email or phone number
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $attemptCredentials = ['email' => $login, 'password' => $password];
        } else {
            $attemptCredentials = ['no_hp' => $login, 'password' => $password];
        }

        if (Auth::attempt($attemptCredentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Arahkan berdasarkan role
            if ($user->isAdmin() || $user->isGuru()) {
                return redirect()->intended(route('dashboard.admin'))
                    ->with('success', 'Login berhasil! Selamat datang ' . $user->name);
            }

            if ($user->isOrangtua()) {
                return redirect()->intended(route('orangtua.dashboard'))
                    ->with('success', 'Login berhasil! Selamat datang ' . $user->name);
            }

            // Jika role tidak dikenali, logout kembali
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akses tidak diizinkan untuk akun ini.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil!');
    }

    public function showChangePassword()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Pakai layout sesuai role
        if ($user->isOrangtua()) {
            return view('orangtua.changePassword');
        }

        return view('auth.changePassword');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $login = $request->input('email');
        
        // Cari user berdasarkan email atau no_hp
        $user = null;
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        } else {
            $user = User::where('no_hp', $login)->first();
        }

        // Cek apakah user ada dan adalah orang tua
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau nomor HP tidak ditemukan.',
            ])->withInput();
        }

        if (!$user->isOrangtua()) {
            return back()->withErrors([
                'email' => 'Fitur reset password hanya tersedia untuk akun Orang Tua. Silakan hubungi admin untuk reset password.',
            ])->withInput();
        }

        // Generate token
        $token = Str::random(64);
        
        // Simpan token ke password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Simpan token di session untuk verifikasi (karena kita tidak kirim email)
        session(['reset_token_' . $user->id => $token]);
        session(['reset_user_id' => $user->id]);

        return redirect()->route('password.reset', ['token' => $token, 'email' => $user->email])
            ->with('success', 'Silakan buat password baru Anda.');
    }

    public function showResetPassword(Request $request, $token = null)
    {
        $email = $request->get('email');
        
        if (!$token || !$email) {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Link reset password tidak valid.']);
        }

        // Cek apakah token valid
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$reset) {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
        }

        // Cek apakah token cocok
        if (!Hash::check($token, $reset->token)) {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Cek apakah token masih valid (60 menit)
        if (now()->diffInMinutes($reset->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Token reset password sudah kadaluarsa. Silakan request ulang.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        // Cek apakah token valid
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Cek apakah token cocok
        if (!Hash::check($token, $reset->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Cek apakah token masih valid (60 menit)
        if (now()->diffInMinutes($reset->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Token reset password sudah kadaluarsa. Silakan request ulang.']);
        }

        // Cari user dan pastikan adalah orang tua
        $user = User::where('email', $email)->first();
        
        if (!$user || !$user->isOrangtua()) {
            return back()->withErrors(['email' => 'Akun tidak ditemukan atau bukan akun Orang Tua.']);
        }

        // Update password
        $user->password = Hash::make($password);
        $user->save();

        // Hapus token reset
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}