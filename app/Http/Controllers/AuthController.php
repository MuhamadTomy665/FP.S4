<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required|string|max:50',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // ✅ Login sebagai petugas dan catat log
        if (Auth::guard('petugas')->attempt($credentials, $remember)) {
            $petugas = Auth::guard('petugas')->user();

            LogAktivitas::create([
                'user_id'   => $petugas->id,
                'aktivitas' => 'Login Petugas',
                'deskripsi' => 'Petugas berhasil login',
                'ip_address'=> $request->ip(),
            ]);

            return redirect()->route('petugas.antrian.index');
        }

        // ✅ Login sebagai admin (tidak dicatat di log_aktivitas)
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            return redirect()->route('dashboard');
        }

        return back()->with('failed', 'Email atau password salah');
    }

    public function logout(Request $request)
    {
        // ✅ Logout petugas dan catat log
        if (Auth::guard('petugas')->check()) {
            $petugas = Auth::guard('petugas')->user();

            LogAktivitas::create([
                'user_id'   => $petugas->id,
                'aktivitas' => 'Logout Petugas',
                'deskripsi' => 'Petugas logout dari sistem',
                'ip_address'=> $request->ip(),
            ]);

            Auth::guard('petugas')->logout();
        }

        // ✅ Logout admin (tidak dicatat)
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}
