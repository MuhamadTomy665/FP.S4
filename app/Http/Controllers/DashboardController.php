<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poli;

class DashboardController extends Controller
{
    // Menampilkan dashboard dengan data poli
    public function index()
    {
        $dataPoli = Poli::all(); // Ambil semua data dari tabel poli
        return view('dashboard', compact('dataPoli'));
    }

    // Menyimpan data poli baru
    public function simpanPoli(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|string',
            'hari' => 'required|array',      // Ubah ke array
            'hari.*' => 'string',            // Validasi tiap elemen array
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'dokter' => 'required|string',
        ]);

        $data = $request->all();
        $data['hari'] = implode(',', $request->hari); // Gabungkan array jadi string

        Poli::create($data);

        return redirect()->route('dashboard')->with('success', 'Poli berhasil ditambahkan.');
    }

    // Menghapus poli berdasarkan ID
    public function hapusPoli($id)
    {
        Poli::destroy($id);

        return redirect()->route('dashboard')->with('success', 'Poli berhasil dihapus.');
    }

    // Mengupdate data poli
    public function updatePoli(Request $request, $id)
    {
        $request->validate([
            'nama_poli' => 'required|string',
            'hari' => 'required|array',      // Ubah ke array
            'hari.*' => 'string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'dokter' => 'required|string',
        ]);

        $poli = Poli::findOrFail($id);

        $data = $request->all();
        $data['hari'] = implode(',', $request->hari); // Gabungkan array jadi string

        $poli->update($data);

        return redirect()->route('dashboard')->with('success', 'Data poli berhasil diperbarui.');
    }

    // Mengambil data poli untuk modal edit (AJAX)
    public function editPoli($id)
    {
        $poli = Poli::findOrFail($id);
        return response()->json($poli);
    }
}
