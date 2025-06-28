<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // ✅ REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|string|max:20|unique:tbl_pasien,nik',
            'no_hp'    => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $pasien = Pasien::create([
            'name'     => trim($request->name),
            'nik'      => trim($request->nik),
            'no_hp'    => trim($request->no_hp),
            'password' => Hash::make(trim($request->password)), // ✅ pastikan password bersih dari spasi
        ]);

        $token = $pasien->createToken('pasien_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil',
            'data'    => $pasien,
            'token'   => $token,
        ], 201);
    }

    // ✅ LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'nik'      => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $nik = trim($request->nik);
        $password = trim($request->password);

        Log::info('Percobaan login', ['nik' => $nik]);

        $pasien = Pasien::whereRaw('TRIM(nik) = ?', [$nik])->first();

        if (!$pasien || !Hash::check($password, $pasien->password)) {
            Log::warning('Login gagal', [
                'nik_input' => $nik,
                'pasien_ditemukan' => (bool) $pasien,
            ]);
            return response()->json([
                'status'  => false,
                'message' => 'NIK atau password salah',
            ], 401);
        }

        Log::info('Login berhasil', ['nik' => $nik]);

        $token = $pasien->createToken('pasien_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => $pasien,
            'token'   => $token,
        ]);
    }

    // ✅ PROFILE
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'data'   => $request->user(),
        ]);
    }

    // ✅ LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil',
        ]);
    }

    // ✅ CEK NIK UNTUK LUPA PASSWORD
    public function cekNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|string'
        ]);

        $nik = trim($request->nik);
        $pasien = Pasien::whereRaw('TRIM(nik) = ?', [$nik])->first();

        if (!$pasien) {
            return response()->json([
                'status'  => false,
                'message' => 'NIK tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'NIK valid'
        ]);
    }

    // ✅ RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            'nik'      => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $nik = trim($request->nik);
        $pasien = Pasien::whereRaw('TRIM(nik) = ?', [$nik])->first();

        if (!$pasien) {
            return response()->json([
                'status'  => false,
                'message' => 'NIK tidak ditemukan'
            ], 404);
        }

        $pasien->password = Hash::make(trim($request->password));
        $pasien->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password berhasil diubah.'
        ]);
    }
}
