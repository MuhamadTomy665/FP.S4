<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KonfigurasiUmum;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KonfigurasiUmumController extends Controller
{
    public function index()
    {
        $konfigurasi = KonfigurasiUmum::first();

        // ✅ Ambil log aktivitas hanya milik petugas (id yang ada di tabel petugas)
        $logs = LogAktivitas::whereIn('user_id', function ($query) {
                $query->select('id')->from('petugas');
            })
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('konfigurasi_umum', compact('konfigurasi', 'logs'));
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

        // ✅ Catat aktivitas hanya jika yang login adalah petugas
        $guard = Auth::guard('petugas')->check() ? 'petugas' : 'web';

        if ($guard === 'petugas') {
            LogAktivitas::create([
                'user_id'   => Auth::guard('petugas')->id(),
                'aktivitas' => 'Update Konfigurasi Umum',
                'deskripsi' => "Jam buka: {$request->jam_buka}, Jam tutup: {$request->jam_tutup}, Kuota: {$request->kuota_antrian}",
                'ip_address'=> $request->ip(),
            ]);
        }

        return redirect()->route('konfigurasi_umum')->with('success', 'Konfigurasi berhasil diperbarui.');
    }
}
