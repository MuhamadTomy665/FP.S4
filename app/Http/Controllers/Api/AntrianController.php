<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AntrianController extends Controller
{
    // ✅ Simpan Antrian Baru
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pasien_id' => 'required|exists:tbl_pasien,id',
                'poli' => 'required|string|max:100',
                'tanggal' => 'required|date',
                'jam' => 'required|string|max:10',
            ]);

            $kuotaPerJam = 10;

            // Cek kuota
            $jumlahPasienJamIni = Antrian::where('tanggal', $validated['tanggal'])
                ->where('jam', $validated['jam'])
                ->count();

            if ($jumlahPasienJamIni >= $kuotaPerJam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota untuk jam ini sudah penuh. Silakan pilih jam lain.'
                ], 409);
            }

            // Hitung nomor antrian
            $jumlahAntrianHariIni = Antrian::where('poli', $validated['poli'])
                ->where('tanggal', $validated['tanggal'])
                ->count();

            $nomorUrut = $jumlahAntrianHariIni + 1;
            $nomorAntrian = 'A' . sprintf('%04d', $nomorUrut);

            // Generate kode dan barcode (✅ prefix langsung ditambahkan)
            $kode = 'ANTRI-' . $validated['pasien_id'] . '-' . now()->format('YmdHis');
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode(
                QrCode::format('png')->size(250)->generate($kode)
            );

            $antrian = Antrian::create([
                'pasien_id' => $validated['pasien_id'],
                'poli' => $validated['poli'],
                'tanggal' => $validated['tanggal'],
                'jam' => $validated['jam'],
                'status' => 'antri',
                'nomor_antrian' => $nomorAntrian,
                'barcode_code' => $qrCodeBase64,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrian berhasil disimpan.',
                'data' => $antrian,
                'barcode_image' => $qrCodeBase64,
                'kode' => $kode,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Gagal simpan antrian: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan antrian.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Ambil Antrian Terakhir untuk Pasien
    public function terakhir($pasien_id)
    {
        try {
            $antrian = Antrian::where('pasien_id', $pasien_id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$antrian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data antrian tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $antrian,
                'barcode_image' => $antrian->barcode_code,
                'kode' => 'ANTRI-' . $antrian->pasien_id . '-' . $antrian->id,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal ambil antrian terakhir: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Ambil Semua Riwayat Antrian untuk Pasien
    public function riwayat($pasien_id)
    {
        try {
            $riwayat = Antrian::where('pasien_id', $pasien_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // ✅ Pastikan semua barcode_code sudah punya prefix
            foreach ($riwayat as $item) {
                if ($item->barcode_code && !str_starts_with($item->barcode_code, 'data:image')) {
                    $item->barcode_code = 'data:image/png;base64,' . $item->barcode_code;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $riwayat
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal ambil riwayat antrian: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil riwayat.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
