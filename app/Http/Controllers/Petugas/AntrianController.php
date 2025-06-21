<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antrian;
use Carbon\Carbon;
use App\Events\PanggilAntrianEvent; // ✅ Event broadcasting untuk realtime

class AntrianController extends Controller
{
    public function index()
    {
        // Ambil semua data antrian hari ini tanpa filter poli
        $dataAntrian = Antrian::with('poli', 'pasien') // relasi poli dan pasien
            ->whereDate('created_at', Carbon::today())
            ->orderBy('status')
            ->orderBy('created_at')
            ->get();

        return view('petugas.antrian', compact('dataAntrian'));
    }

    public function panggil($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->status = 'dipanggil';
        $antrian->waktu_dipanggil = now();
        $antrian->save();

        // ✅ Kirim event real-time ke frontend (pasien)
        event(new PanggilAntrianEvent($antrian));

        return redirect()->route('petugas.antrian.index')->with('success', 'Pasien telah dipanggil.');
    }

    public function selesai($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->status = 'selesai';
        $antrian->waktu_selesai = now();
        $antrian->save();

        return redirect()->route('petugas.antrian.index')->with('success', 'Layanan selesai.');
    }
}
