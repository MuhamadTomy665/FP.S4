<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poli;

class PencarianController extends Controller
{
    public function cari(Request $request)
    {
        $q = $request->query('q');

        $poli = Poli::where('nama_poli', 'like', "%{$q}%")
                    ->orWhere('dokter', 'like', "%{$q}%")
                    ->get([
                        'id',
                        'nama_poli as nama',
                        'dokter',
                        'hari',
                        'jam_mulai',
                        'jam_selesai'
                    ]);

        return response()->json([
            'poli' => $poli,
            'dokter' => [] // Kosongkan jika belum ada tabel dokter
        ]);
    }
}
