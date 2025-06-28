<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KonfigurasiUmumController;
use App\Http\Controllers\Petugas\AntrianController;
use App\Http\Controllers\Petugas\PantauWaktuController;
use App\Http\Controllers\LogAktivitasController; // ✅ Tambahan

// ===============================
// ✅ Halaman Awal & Login Umum
// ===============================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===============================
// ✅ PETUGAS ROUTES (guard: petugas)
// ===============================
Route::middleware(['auth:petugas'])->group(function () {
    // Halaman antrian petugas
    Route::get('/petugas/antrian', [AntrianController::class, 'index'])->name('petugas.antrian.index');

    // ✅ Alias supaya route('petugas.antrian') tidak error
    Route::get('/petugas/antrian-alias', fn () => redirect()->route('petugas.antrian.index'))->name('petugas.antrian');

    // Cetak antrian
    Route::get('/petugas/antrian/{id}/cetak', [AntrianController::class, 'cetak'])->name('petugas.antrian.cetak');

    // Aksi antrian
    Route::post('/petugas/antrian/{id}/panggil', [AntrianController::class, 'panggil'])->name('petugas.antrian.panggil');
    Route::post('/petugas/antrian/{id}/selesai', [AntrianController::class, 'selesai'])->name('petugas.antrian.selesai');

    // Scan QR (jika masih digunakan)
    Route::get('/petugas/scan', [AntrianController::class, 'scan'])->name('petugas.scan');

    // Update status via QR
    Route::post('/petugas/antrian/update-status', [AntrianController::class, 'updateStatusByQR'])->name('petugas.antrian.updateStatus');

    // ✅ Pantau waktu & efisiensi
    Route::get('/petugas/waktu-efisiensi', [PantauWaktuController::class, 'index'])->name('petugas.waktu-efisiensi');
});

// ===============================
// ✅ ADMIN ROUTES (guard: web)
// ===============================
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Poli
    Route::post('/poli', [DashboardController::class, 'simpanPoli'])->name('poli.simpan');
    Route::get('/poli/{id}/edit', [DashboardController::class, 'editPoli'])->name('poli.edit');
    Route::put('/poli/{id}', [DashboardController::class, 'updatePoli'])->name('poli.update');
    Route::delete('/poli/{id}', [DashboardController::class, 'hapusPoli'])->name('poli.hapus');

    // Kelola Petugas
    Route::get('/petugas', [PetugasController::class, 'index'])->name('kelola_petugas');
    Route::post('/petugas', [PetugasController::class, 'simpan'])->name('petugas.simpan');
    Route::delete('/petugas/{id}', [PetugasController::class, 'hapus'])->name('petugas.hapus');

    // Konfigurasi Umumx
    Route::get('/konfigurasi', [KonfigurasiUmumController::class, 'index'])->name('konfigurasi_umum');
    Route::post('/konfigurasi', [KonfigurasiUmumController::class, 'update'])->name('konfigurasi.update');

    // Laporan Antrian
    Route::get('/laporan-antrian', [LaporanController::class, 'index'])->name('laporan_antrian');

    // ✅ Log Aktivitas (admin)
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log_aktivitas');

    Route::post('/cek-nik', [PasienController::class, 'cekNik']);
Route::post('/reset-password', [PasienController::class, 'resetPassword']);

});
