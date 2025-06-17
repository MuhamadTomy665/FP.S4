<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function index()
    {
        $petugas = Auth::guard('petugas')->user();

        // Ambil ID poli yang diakses petugas
        $poliIds = $petugas->polis->pluck('id')->toArray();

        // Ambil data antrian hari ini untuk poli yang diakses petugas
        $dataAntrian = Antrian::whereDate('created_at', Carbon::today())
            ->whereIn('poli_id', $poliIds)
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
