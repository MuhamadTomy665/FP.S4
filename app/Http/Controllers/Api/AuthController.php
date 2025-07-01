<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * ---------------------------------
     * REGISTER
     * ---------------------------------
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|string|digits:16|unique:tbl_pasien,nik',
            'no_hp'    => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        // Trim dan sanitasi input
        $name     = trim($request->name);
        $nik      = preg_replace('/\s+/', '', $request->nik);
        $no_hp    = trim($request->no_hp);
        $password = trim($request->password);

        // Buat pasien
        $pasien = Pasien::create([
            'name'     => $name,
            'nik'      => $nik,
            'no_hp'    => $no_hp,
            'password' => Hash::make($password),
        ]);

        $token = $pasien->createToken('pasien_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil',
            'data'    => $pasien,
            'token'   => $token,
        ], 201);
    }

    /**
     * ---------------------------------
     * LOGIN
     * ---------------------------------
     */
    public function login(Request $request)
    {
        $request->validate([
            'nik'      => 'required|string|digits:16',
            'password' => 'required|string|min:6',
        ]);

        $nik      = preg_replace('/\s+/', '', $request->nik);
        $password = trim($request->password);

        $pasien = Pasien::where('nik', $nik)->first();

        if (!$pasien || !Hash::check($password, $pasien->password)) {
            Log::warning('Login gagal', [
                'nik_input'         => $nik,
                'pasien_ditemukan'  => (bool) $pasien,
                'hash_check_result' => $pasien ? Hash::check($password, $pasien->password) : null,
                'input_pw_sample'   => substr($password, 0, 20),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'NIK atau password salah',
            ], 401);
        }

        $token = $pasien->createToken('pasien_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => $pasien,
            'token'   => $token,
        ]);
    }

    /** PROTECTED ROUTES */
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'data'   => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * ---------------------------------
     * LUPA PASSWORD (CEK NIK & RESET)
     * ---------------------------------
     */
    public function cekNik(Request $request)
    {
        $request->validate(['nik' => 'required|string|digits:16']);

        $nik    = preg_replace('/\s+/', '', $request->nik);
        $pasien = Pasien::where('nik', $nik)->first();

        if (!$pasien) {
            return response()->json([
                'status'  => false,
                'message' => 'NIK tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'NIK valid',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'nik'      => 'required|string|digits:16',
            'password' => 'required|string|min:6',
        ]);

        $nik           = preg_replace('/\s+/', '', $request->nik);
        $password      = trim($request->password);

        $pasien = Pasien::where('nik', $nik)->first();

        if (!$pasien) {
            return response()->json([
                'status'  => false,
                'message' => 'NIK tidak ditemukan',
            ], 404);
        }

        $pasien->password = Hash::make($password);
        $pasien->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }
}
