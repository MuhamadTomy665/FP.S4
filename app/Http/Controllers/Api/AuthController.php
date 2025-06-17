<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:tbl_pasien,nik',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $pasien = Pasien::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
        ]);

        $token = $pasien->createToken('pasien_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'data' => $pasien,
            'token' => $token,
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
        ]);

        Log::info('Percobaan login', [
            'nik' => $request->nik,
            // Jangan log password demi keamanan
        ]);

        $pasien = Pasien::where('nik', $request->nik)->first();

        if (!$pasien) {
            Log::warning('Login gagal: NIK tidak ditemukan', ['nik' => $request->nik]);
            return response()->json([
                'status' => false,
                'message' => 'NIK atau password salah',
            ], 401);
        }

        if (!Hash::check($request->password, $pasien->password)) {
            Log::warning('Login gagal: Password salah', ['nik' => $request->nik]);
            return response()->json([
                'status' => false,
                'message' => 'NIK atau password salah',
            ], 401);
        }

        Log::info('Login berhasil', ['nik' => $request->nik]);

        $token = $pasien->createToken('pasien_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => $pasien,
            'token' => $token,
        ]);
    }

    // PROFILE
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => $request->user(),
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}
