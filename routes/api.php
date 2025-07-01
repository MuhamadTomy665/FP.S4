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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/cek-nik', [AuthController::class, 'cekNik']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// ==============================
// 📌 Endpoint Profil (Butuh Token Login dari Pasien)
// ==============================
Route::middleware('auth:sanctum')->group(function () {

    // 👤 Profil
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());

    // 🏥 Antrian (dengan auth & ID dari token)
    Route::post('/antrian', [AntrianController::class, 'store']);
    Route::get('/antrian/terakhir', [AntrianController::class, 'terakhir']);     // tanpa {id}
    Route::get('/antrian/riwayat', [AntrianController::class, 'riwayat']);       // tanpa {id}
    Route::post('/antrian/{id}/batal', [AntrianController::class, 'batalkan']);  // tetap pakai ID antrian
});

// ==============================
// 📌 Poli
// ==============================
Route::get('/poli', [PoliController::class, 'index']);
Route::get('/poli/{id}', [PoliController::class, 'show']);

// ==============================
// 📌 Kuota per jam (tidak perlu login karena hanya membaca data)
// ==============================
Route::get('/antrian/kuota', [AntrianController::class, 'getKuotaPerJam']);

// ==============================
// 📌 Pencarian
// ==============================
Route::get('/pencarian', [PencarianController::class, 'cari']);
