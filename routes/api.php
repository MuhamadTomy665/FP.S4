<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PoliController;
use App\Http\Controllers\Api\AntrianController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua endpoint API yang digunakan oleh frontend mobile (Ionic)
*/

// ==============================
// 📌 AUTH (Pasien: Registrasi & Login)
// ==============================
Route::post('/register', [AuthController::class, 'register']); // daftar pasien baru ke tbl_pasien
Route::post('/login', [AuthController::class, 'login']);       // login berdasarkan NIK & password

// ==============================
// 📌 Endpoint Profil (Butuh Token Login dari Pasien)
// ==============================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());
});

// ==============================
// 📌 Poli
// ==============================
Route::get('/poli', [PoliController::class, 'index']);     // list semua poli
Route::get('/poli/{id}', [PoliController::class, 'show']); // detail satu poli

// ==============================
// 📌 Antrian
// ==============================
Route::post('/antrian', [AntrianController::class, 'store']);               // pasien mendaftar antrian
Route::get('/antrian/terakhir/{id}', [AntrianController::class, 'terakhir']); // ✅ ambil antrian terakhir pasien
