<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Antrian;
use Carbon\Carbon;
use App\Events\PanggilAntrianEvent;

class AntrianController extends Controller
{
    public function index()
    {
        $petugas = Auth::user(); // ambil petugas yang sedang login
        $aksesPoli = $petugas->akses_poli; // diasumsikan menyimpan nama poli

        $dataAntrian = Antrian::with('poli', 'pasien')
            ->whereDate('tanggal', Carbon::today()) // hanya hari ini
            ->where('poli', $aksesPoli) // filter berdasarkan poli yang diakses
            ->orderBy('status')
            ->orderBy('jam')
            ->get();

        return view('petugas.antrian', compact('dataAntrian'));
    }

    public function panggil($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->status = 'dipanggil';
        $antrian->waktu_dipanggil = now();
        $antrian->save();

        // 🔔 Kirim event ke frontend via broadcasting
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

    // ✅ Halaman scan QR
    public function scan()
    {
        return view('petugas.scan');
    }

    // ✅ Update status berdasarkan QR code (dari hasil scan)
    public function updateStatusByQR(Request $request)
    {
        $request->validate([
            'kode' => 'required|string',
        ]);

        $antrian = Antrian::where('barcode_code', 'like', '%' . $request->kode . '%')->first();

        if (!$antrian) {
            return response()->json([
                'success' => false,
                'message' => 'Antrian tidak ditemukan.',
            ], 404);
        }

        $antrian->status = 'selesai';
        $antrian->waktu_selesai = now();
        $antrian->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui menjadi selesai.',
        ]);
    }

    // ✅ CETAK QR Antrian
    public function cetak($id)
    {
        $antrian = Antrian::with('pasien')->findOrFail($id);
        return view('petugas.cetak', compact('antrian'));
    }
}
