<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\Poli;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    // Menampilkan halaman kelola petugas
    public function index()
    {
        $petugasList = Petugas::all();
        $allPoli = Poli::all();

        // View: resources/views/layout/kelola_petugas.blade.php
        return view('kelola_petugas', compact('petugasList', 'allPoli'));
    }

    // Menyimpan petugas baru
    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('petugas')], // ganti dari tbl_petugas ke petugas
            'password' => 'required|string|min:6',
            'akses_poli' => 'required|array',
            'akses_poli.*' => 'exists:tbl_poli,id', // tetap tbl_poli sesuai database
        ]);

        Petugas::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'akses_poli' => $request->akses_poli, // otomatis json cast di model
            'role' => 'petugas', // default role
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
