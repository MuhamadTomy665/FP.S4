<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PoliController;
use App\Http\Controllers\Api\AntrianController;
use App\Http\Controllers\Api\PencarianController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua endpoint API yang digunakan oleh frontend mobile (Ionic)
*/

// ==============================
// 📌 AUTH (Pasien: Registrasi, Login, Lupa Password)
// ==============================
Route::post('/register', [AuthController::class, 'register']);              // Daftar pasien baru
Route::post('/login', [AuthController::class, 'login']);                    // Login dengan NIK & password
Route::post('/cek-nik', [AuthController::class, 'cekNik']);                // Cek apakah NIK valid (lupa password)
Route::post('/reset-password', [AuthController::class, 'resetPassword']);  // Reset password berdasarkan NIK

// ==============================
// 📌 Endpoint Profil (Butuh Token Login dari Pasien)
// ==============================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);    // Info akun login
    Route::post('/logout', [AuthController::class, 'logout']);     // Logout & hapus token
    Route::get('/user', fn (Request $request) => $request->user()); // Get user auth info
});

// ==============================
// 📌 Poli
// ==============================
Route::get('/poli', [PoliController::class, 'index']);     // List semua poli
Route::get('/poli/{id}', [PoliController::class, 'show']); // Detail poli by ID

// ==============================
// 📌 Antrian
// ==============================
Route::post('/antrian', [AntrianController::class, 'store']);                  // Daftar antrian
Route::get('/antrian/terakhir/{id}', [AntrianController::class, 'terakhir']);  // Antrian terakhir pasien
Route::get('/antrian/riwayat/{id}', [AntrianController::class, 'riwayat']);    // Riwayat antrian
Route::get('/antrian/kuota', [AntrianController::class, 'getKuotaPerJam']);    // Kuota per jam
Route::post('/antrian/{id}/batal', [AntrianController::class, 'batalkan']);    // ✅ Batalkan antrian

// ==============================
// 📌 Pencarian
// ==============================
Route::get('/pencarian', [PencarianController::class, 'cari']); // Pencarian antrian
