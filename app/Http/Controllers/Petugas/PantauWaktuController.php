<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antrian;

class PantauWaktuController extends Controller
{
    public function index()
    {
        $data = Antrian::selectRaw('
                    id,
                    TIMESTAMPDIFF(SECOND, created_at, waktu_dipanggil) as waktu_tunggu,
                    TIMESTAMPDIFF(SECOND, waktu_dipanggil, waktu_selesai) as lama_layanan
                ')
                ->whereNotNull('waktu_dipanggil')
                ->whereNotNull('waktu_selesai')
                ->orderByDesc('id')
                ->get();

        return view('petugas.waktu-efisiensi', compact('data'));
    }
}
