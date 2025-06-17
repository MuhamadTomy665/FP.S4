<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $totalPasien = Antrian::count();

        $rataRataWaktuTunggu = Antrian::avg('waktu_tunggu');
        $rataRataWaktuTunggu = $rataRataWaktuTunggu ? round($rataRataWaktuTunggu, 2) . ' menit' : '0 menit';

        $performaHariRaw = Antrian::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as total_pasien'),
            DB::raw('AVG(waktu_tunggu) as rata_rata_tunggu')
        )
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'desc')
        ->get();

        $performaHari = [];
        foreach ($performaHariRaw as $item) {
            $performaHari[$item->tanggal] = [
                'total_pasien' => $item->total_pasien,
                'rata_rata_tunggu' => round($item->rata_rata_tunggu, 2),
            ];
        }

        $performaPoliRaw = Antrian::select(
            'poli.nama_poli',
            DB::raw('COUNT(antrian.id) as total_pasien'),
            DB::raw('AVG(antrian.waktu_tunggu) as rata_rata_tunggu')
        )
        ->join('tbl_poli as poli', 'antrian.poli_id', '=', 'poli.id')
        ->groupBy('poli.id', 'poli.nama_poli')
        ->orderBy('poli.nama_poli')
        ->get();

        $performaPoli = $performaPoliRaw->map(function($item){
            return [
                'nama_poli' => $item->nama_poli,
                'total_pasien' => $item->total_pasien,
                'rata_rata_tunggu' => round($item->rata_rata_tunggu, 2),
            ];
        })->toArray();

        return view('laporan_antrian', compact(
            'totalPasien', 
            'rataRataWaktuTunggu', 
            'performaHari', 
            'performaPoli'
        ));
    }
}
