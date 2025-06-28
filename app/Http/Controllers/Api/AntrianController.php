<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class AntrianController extends Controller
{
    /* =========================================================
       DAFTAR / STORE
       ========================================================= */
    public function store(Request $request)
    {
        try {
            /* ---------- Validasi ---------- */
            $validated = $request->validate([
                'pasien_id' => 'required|exists:tbl_pasien,id',
                'poli'      => 'required|string|max:100',
                'tanggal'   => 'required|date',
                'jam'       => 'required|string|max:10',
            ]);

            /* ---------- Cek duplikat ---------- */
            $duplikat = Antrian::where('pasien_id', $validated['pasien_id'])
                ->where('poli',  $validated['poli'])
                ->where('tanggal', $validated['tanggal'])
                ->whereNotIn('status', ['dibatalkan', 'terlewat'])
                ->first();

            if ($duplikat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki antrian di poli ini untuk tanggal tersebut.'
                ], 409);
            }

            /* ---------- Cek kuota per jam ---------- */
            $kuotaPerJam   = 6;
            $intervalMenit = 10;

            $jumlahPasienJamIni = Antrian::where('tanggal', $validated['tanggal'])
                ->where('jam', '>=', $validated['jam'])
                ->where('jam', '<', Carbon::createFromFormat('H:i', $validated['jam'])->addHour()->format('H:i'))
                ->where('poli', $validated['poli'])
                ->count();

            if ($jumlahPasienJamIni >= $kuotaPerJam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota untuk jam ini sudah penuh. Silakan pilih jam lain.'
                ], 409);
            }

            /* ---------- Hitung jam akhir & nomor urut ---------- */
            $jamAwal     = Carbon::createFromFormat('H:i', $validated['jam']);
            $jamFinal    = $jamAwal->copy()->addMinutes($jumlahPasienJamIni * $intervalMenit);
            $jamAkhirStr = $jamFinal->format('H:i');

            $nomorUrut    = Antrian::where('poli', $validated['poli'])
                             ->where('tanggal', $validated['tanggal'])
                             ->count() + 1;
            $nomorAntrian = 'A' . sprintf('%04d', $nomorUrut);

            /* ---------- Generate QR lebih dulu ---------- */
            $kode         = 'ANTRI-' . strtoupper(uniqid());
            $qrBase64     = 'data:image/png;base64,' .
                            base64_encode(QrCode::format('png')->size(250)->generate($kode));

            /* ---------- Simpan antrian sekaligus QR ---------- */
            $antrian = Antrian::create([
                'pasien_id'     => $validated['pasien_id'],
                'poli'          => $validated['poli'],
                'tanggal'       => $validated['tanggal'],
                'jam'           => $jamAkhirStr,
                'status'        => 'antri',
                'nomor_antrian' => $nomorAntrian,
                'barcode_code'  => $qrBase64,
            ]);

            return response()->json([
                'success'       => true,
                'message'       => 'Antrian berhasil disimpan.',
                'data'          => $antrian,
                'barcode_image' => $qrBase64,
                'kode'          => $kode,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Gagal simpan antrian: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan antrian.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       TERAKHIR
       ========================================================= */
    public function terakhir($pasien_id)
    {
        try {
            $antrian = Antrian::where('pasien_id', $pasien_id)->latest()->first();
            if (!$antrian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data antrian tidak ditemukan.'
                ], 404);
            }

            if ($antrian->barcode_code && !str_starts_with($antrian->barcode_code, 'data:image')) {
                $antrian->barcode_code = 'data:image/png;base64,' . $antrian->barcode_code;
            }

            $this->markIfPast($antrian);

            return response()->json([
                'success'       => true,
                'data'          => $antrian,
                'barcode_image' => $antrian->barcode_code,
                'kode'          => 'ANTRI-'.$antrian->pasien_id.'-'.$antrian->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal ambil antrian terakhir: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       RIWAYAT
       ========================================================= */
    public function riwayat($pasien_id)
    {
        try {
            $riwayat = Antrian::where('pasien_id', $pasien_id)
                        ->orderByDesc('created_at')
                        ->get();

            foreach ($riwayat as $item) {
                if ($item->barcode_code && !str_starts_with($item->barcode_code, 'data:image')) {
                    $item->barcode_code = 'data:image/png;base64,'.$item->barcode_code;
                }
                $this->markIfPast($item);
            }

            return response()->json([
                'success' => true,
                'data'    => $riwayat
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal ambil riwayat antrian: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil riwayat.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       KUOTA PER JAM
       ========================================================= */
    public function getKuotaPerJam(Request $request)
    {
        try {
            $validated = $request->validate([
                'poli'    => 'required|string|max:100',
                'tanggal' => 'required|date',
            ]);

            $kuotaPerJam   = 6;
            $jamOperasional = [
                '08:00','09:00','10:00','11:00','12:00',
                '13:00','14:00','15:00','16:00','17:00',
            ];

            $result = [];
            foreach ($jamOperasional as $jam) {
                $jumlahPasien = Antrian::where('tanggal', $validated['tanggal'])
                    ->where('jam', '>=', $jam)
                    ->where('jam', '<', Carbon::createFromFormat('H:i', $jam)->addHour()->format('H:i'))
                    ->where('poli', $validated['poli'])
                    ->count();

                $result[] = [
                    'jam'     => $jam,
                    'kuota'   => $kuotaPerJam,
                    'terisi'  => $jumlahPasien,
                    'tersisa' => max($kuotaPerJam - $jumlahPasien, 0),
                    'penuh'   => $jumlahPasien >= $kuotaPerJam,
                ];
            }

            return response()->json(['success' => true, 'data' => $result]);

        } catch (\Exception $e) {
            Log::error('Gagal ambil kuota per jam: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kuota per jam.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       BATALKAN
       ========================================================= */
    public function batalkan($id)
    {
        try {
            $antrian = Antrian::find($id);
            if (!$antrian) {
                return response()->json(['success' => false, 'message' => 'Data antrian tidak ditemukan.'], 404);
            }

            if (!in_array($antrian->status, ['antri','menunggu'])) {
                return response()->json(['success' => false, 'message' => 'Antrian tidak bisa dibatalkan.'], 400);
            }

            $antrian->status = 'dibatalkan';
            $antrian->save();

            return response()->json(['success' => true, 'message' => 'Antrian berhasil dibatalkan.', 'data' => $antrian]);

        } catch (\Exception $e) {
            Log::error('Gagal membatalkan antrian: '.$e->getMessage());
            return response()->json(['success' => false,'message' => 'Terjadi kesalahan saat membatalkan antrian.','error' => $e->getMessage()], 500);
        }
    }

    /* =========================================================
       Helper: Tandai \"terlewat\"
       ========================================================= */
   private function markIfPast(Antrian $antrian): void
{
    try {
        if (!in_array($antrian->status, ['antri','menunggu','dipanggil'])) {
            return;
        }

        if (!$antrian->tanggal || !$antrian->jam) {
            return; // abaikan jika data tidak lengkap
        }

        $waktu = Carbon::parse("{$antrian->tanggal} {$antrian->jam}");

        if ($waktu->lt(Carbon::now())) {
            $antrian->status = 'terlewat';
        }
    } catch (\Exception $e) {
        Log::error('Gagal tandai antrian terlewat: ' . $e->getMessage());
    }
}
}