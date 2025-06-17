<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KonfigurasiUmumController;
use App\Http\Controllers\Petugas\AntrianController;

Route::get('/petugas/antrian/{id}/cetak', [AntrianController::class, 'cetak'])->name('petugas.antrian.cetak');


// Routes untuk petugas dengan middleware auth guard petugas
Route::middleware(['auth:petugas'])->group(function () {
    // Dashboard petugas (ganti path sesuai kebutuhan)
    Route::get('/petugas/dashboard', [AntrianController::class, 'index'])->name('petugas.dashboard');

    // Manajemen antrian petugas
    Route::get('/petugas/antrian', [AntrianController::class, 'index'])->name('petugas.antrian.index');
    Route::post('/petugas/antrian/{id}/panggil', [AntrianController::class, 'panggil'])->name('petugas.antrian.panggil');
    Route::post('/petugas/antrian/{id}/selesai', [AntrianController::class, 'selesai'])->name('petugas.antrian.selesai');
});

// Halaman welcome
Route::get('/', function () {
    return view('welcome');
});

// Halaman login
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Route logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes admin (web guard)
Route::middleware(['auth', 'prevent-back-history'])->group(function () {

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

    // Konfigurasi Umum
    Route::get('/konfigurasi', [KonfigurasiUmumController::class, 'index'])->name('konfigurasi_umum');
    Route::post('/konfigurasi', [KonfigurasiUmumController::class, 'update'])->name('konfigurasi.update');

    // Laporan Antrian
    Route::get('/laporan-antrian', [LaporanController::class, 'index'])->name('laporan_antrian');
});
