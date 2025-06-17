<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required|string|max:50',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember'); // ✅ aman, true jika checkbox dicentang

        // Coba login petugas dulu dengan guard 'petugas'
        if (Auth::guard('petugas')->attempt($credentials, $remember)) {
            return redirect()->route('petugas.dashboard');
        }

        // Jika gagal, coba login user/admin dengan guard 'web'
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            return redirect()->route('dashboard');
        }

        return back()->with('failed', 'Email atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('petugas')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}
