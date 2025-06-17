<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KonfigurasiUmum;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class KonfigurasiUmumController extends Controller
{
    public function index()
    {
        $konfigurasi = KonfigurasiUmum::first();
        return view('konfigurasi_umum', compact('konfigurasi'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'kuota_antrian' => 'required|integer|min:1',
        ]);

        $konfigurasi = KonfigurasiUmum::first();
        if (!$konfigurasi) {
            $konfigurasi = new KonfigurasiUmum();
        }

        $konfigurasi->jam_buka = $request->jam_buka;
        $konfigurasi->jam_tutup = $request->jam_tutup;
        $konfigurasi->kuota_antrian = $request->kuota_antrian;
        $konfigurasi->save();

        // Simpan log aktivitas pengguna
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Konfigurasi Umum',
            'deskripsi' => "Jam buka: {$request->jam_buka}, Jam tutup: {$request->jam_tutup}, Kuota: {$request->kuota_antrian}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('konfigurasi_umum')->with('success', 'Konfigurasi berhasil diperbarui.');
    }
}
