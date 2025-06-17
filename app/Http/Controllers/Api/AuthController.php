<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make($request->password), // pastikan password di-hash di sini
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

        $pasien = Pasien::where('nik', $request->nik)->first();

        if (!$pasien || !Hash::check($request->password, $pasien->password)) {
            return response()->json([
                'status' => false,
                'message' => 'NIK atau password salah',
            ], 401);
        }

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
