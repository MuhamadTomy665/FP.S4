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

            $kuotaPerJam = 6;

            $jumlahPasienJamIni = Antrian::where('tanggal', $validated['tanggal'])
                ->where('jam', $validated['jam'])
                ->where('poli', $validated['poli'])
                ->count();

            if ($jumlahPasienJamIni >= $kuotaPerJam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota untuk jam ini sudah penuh. Silakan pilih jam lain.'
                ], 409);
            }

            $jumlahAntrianHariIni = Antrian::where('poli', $validated['poli'])
                ->where('tanggal', $validated['tanggal'])
                ->count();

            $nomorUrut = $jumlahAntrianHariIni + 1;
            $nomorAntrian = 'A' . sprintf('%04d', $nomorUrut);

            // Simpan antrian terlebih dahulu tanpa barcode
            $antrian = Antrian::create([
                'pasien_id' => $validated['pasien_id'],
                'poli' => $validated['poli'],
                'tanggal' => $validated['tanggal'],
                'jam' => $validated['jam'],
                'status' => 'antri',
                'nomor_antrian' => $nomorAntrian,
            ]);

            // Setelah ID tersedia, buat kode unik dan barcode
            $kode = 'ANTRI-' . $antrian->id . '-' . strtoupper(uniqid());
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode(
                QrCode::format('png')->size(250)->generate($kode)
            );

            $antrian->update([
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

    public function riwayat($pasien_id)
    {
        try {
            $riwayat = Antrian::where('pasien_id', $pasien_id)
                ->orderBy('created_at', 'desc')
                ->get();

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

    public function getKuotaPerJam(Request $request)
    {
        try {
            $validated = $request->validate([
                'poli' => 'required|string|max:100',
                'tanggal' => 'required|date',
            ]);

            $kuotaPerJam = 6;

            $jamOperasional = [
                '08:00', '09:00', '10:00', '11:00', '12:00',
                '13:00', '14:00', '15:00', '16:00', '17:00',
                '18:00', '19:00', '20:00', '21:00'
            ];

            $result = [];

            foreach ($jamOperasional as $jam) {
                $jumlahPasien = Antrian::where('tanggal', $validated['tanggal'])
                    ->where('jam', $jam)
                    ->where('poli', $validated['poli'])
                    ->count();

                $tersisa = $kuotaPerJam - $jumlahPasien;
                if ($tersisa < 0) $tersisa = 0;

                $result[] = [
                    'jam' => $jam,
                    'kuota' => $kuotaPerJam,
                    'terisi' => $jumlahPasien,
                    'tersisa' => $tersisa,
                    'penuh' => $jumlahPasien >= $kuotaPerJam,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $result,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal ambil kuota per jam: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kuota per jam.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
