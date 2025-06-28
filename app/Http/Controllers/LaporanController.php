<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // Hitung total pasien
        $totalPasien = Antrian::count();

        // Hitung rata-rata waktu tunggu (selisih jam dan waktu_dipanggil dalam menit)
        $rataRataWaktuTunggu = Antrian::whereNotNull('waktu_dipanggil')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, jam, waktu_dipanggil)) as rata_rata'))
            ->value('rata_rata');

        $rataRataWaktuTunggu = $rataRataWaktuTunggu ? round($rataRataWaktuTunggu, 2) . ' menit' : '0 menit';

        // Performa per hari
        $performaHariRaw = Antrian::whereNotNull('waktu_dipanggil')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as total_pasien'),
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, jam, waktu_dipanggil)) as rata_rata_tunggu')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'desc')
            ->get();

        $performaHari = [];
        foreach ($performaHariRaw as $item) {
            $performaHari[$item->tanggal] = [
                'total_pasien' => $item->total_pasien,
                'rata_rata_tunggu' => round($item->rata_rata_tunggu, 2),
            ];
        }

        // Performa per poli
        $performaPoliRaw = Antrian::whereNotNull('waktu_dipanggil')
            ->join('tbl_poli as poli', 'tbl_antrian.poli', '=', 'poli.nama_poli')
            ->select(
                'poli.nama_poli',
                DB::raw('COUNT(tbl_antrian.id) as total_pasien'),
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, jam, waktu_dipanggil)) as rata_rata_tunggu')
            )
            ->groupBy('poli.nama_poli')
            ->orderBy('poli.nama_poli')
            ->get();

        $performaPoli = $performaPoliRaw->map(function ($item) {
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
