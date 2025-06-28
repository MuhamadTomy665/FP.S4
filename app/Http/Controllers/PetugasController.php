<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\Poli;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PetugasController extends Controller
{
    // Menampilkan halaman kelola petugas
    public function index()
    {
        $petugasList = Petugas::all();
        $allPoli = Poli::all();

        return view('kelola_petugas', compact('petugasList', 'allPoli'));
    }

    // Menyimpan petugas baru
    public function simpan(Request $request)
    {
        // ✅ Log data masuk ke controller
        Log::info('🟢 Form Tambah Petugas Masuk:', $request->all());

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('petugas')],
            'password' => 'required|string|min:6',
            'akses_poli' => 'required|array',
            'akses_poli.*' => 'exists:tbl_poli,id',
        ]);

        // Ambil nama_poli berdasarkan ID yang dikirim
        $poliNames = Poli::whereIn('id', $request->akses_poli)->pluck('nama_poli')->toArray();

        Petugas::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password, // di-hash otomatis oleh model
            'akses_poli' => $poliNames,       // Laravel otomatis cast ke JSON
            'role' => 'petugas',
        ]);

        return redirect()->route('kelola_petugas')->with('success', 'Petugas berhasil ditambahkan.');
    }

    // Menghapus petugas
    public function hapus($id)
    {
        Petugas::destroy($id);

        return redirect()->route('kelola_petugas')->with('success', 'Petugas berhasil dihapus.');
    }
}
