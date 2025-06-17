<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AntrianController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pasien_id' => 'required|exists:tbl_pasien,id',
                'poli' => 'required|string|max:100',
                'tanggal' => 'required|date',
                'jam' => 'required|string|max:10',
            ]);

            $jumlahAntrianHariIni = Antrian::where('poli', $validated['poli'])
                ->where('tanggal', $validated['tanggal'])
                ->count();

            $nomorAntrian = $jumlahAntrianHariIni + 1;
            $kode = 'ANTRI-' . $validated['pasien_id'] . '-' . now()->format('YmdHis');

            // ✅ Ganti Barcode ke QR Code (base64 PNG)
            $qrCodeBase64 = base64_encode(
                QrCode::format('png')->size(250)->generate($kode)
            );

            $antrian = Antrian::create([
                'pasien_id' => $validated['pasien_id'],
                'poli' => $validated['poli'],
                'tanggal' => $validated['tanggal'],
                'jam' => $validated['jam'],
                'status' => 'menunggu',
                'nomor_antrian' => $nomorAntrian,
                'barcode_code' => $qrCodeBase64, // ⬅️ Simpan base64 QR code
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrian berhasil disimpan.',
                'data' => $antrian,
                'barcode_image' => 'data:image/png;base64,' . $qrCodeBase64,
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
                'barcode_image' => 'data:image/png;base64,' . $antrian->barcode_code,
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
}
