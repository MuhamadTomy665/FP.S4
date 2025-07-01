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
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'poli'     => 'required|string|max:100',
                'tanggal'  => 'required|date',
                'jam'      => 'required|string|max:10',
            ]);

            $pasienId = auth()->id();
            if (!$pasienId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $duplikat = Antrian::where('pasien_id', $pasienId)
                ->where('poli', $validated['poli'])
                ->where('tanggal', $validated['tanggal'])
                ->whereNotIn('status', ['dibatalkan', 'terlewat'])
                ->first();

            if ($duplikat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki antrian di poli ini untuk tanggal tersebut.'
                ], 409);
            }

            $kuotaPerJam = 6;
            $intervalMenit = 10;

            $jumlahPasienJamIni = Antrian::where('tanggal', $validated['tanggal'])
                ->where('jam', '>=', $validated['jam'])
                ->where('jam', '<', Carbon::createFromFormat('H:i', $validated['jam'])->addHour()->format('H:i'))
                ->where('poli', $validated['poli'])
                ->count();

            if ($jumlahPasienJamIni >= $kuotaPerJam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota untuk jam ini sudah penuh.'
                ], 409);
            }

            $jamAwal = Carbon::createFromFormat('H:i', $validated['jam']);
            $jamAkhirStr = $jamAwal->addMinutes($jumlahPasienJamIni * $intervalMenit)->format('H:i');

            $nomorUrut = Antrian::where('poli', $validated['poli'])
                ->where('tanggal', $validated['tanggal'])
                ->count() + 1;

            $nomorAntrian = 'A' . sprintf('%04d', $nomorUrut);

            $kode = 'ANTRI-' . strtoupper(uniqid());
            $qrBase64 = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(250)->generate($kode));

            $antrian = Antrian::create([
                'pasien_id'     => $pasienId,
                'poli'          => $validated['poli'],
                'tanggal'       => $validated['tanggal'],
                'jam'           => $jamAkhirStr,
                'status'        => 'antri',
                'nomor_antrian' => $nomorAntrian,
                'barcode_code'  => $qrBase64,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrian berhasil disimpan.',
                'data' => $antrian,
                'barcode_image' => $qrBase64,
                'kode' => $kode,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Gagal simpan antrian: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.', 'error' => $e->getMessage()], 500);
        }
    }

    public function terakhir()
    {
        try {
            $pasienId = auth()->id();
            if (!$pasienId) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

            $antrian = Antrian::where('pasien_id', $pasienId)->latest()->first();
            if (!$antrian) return response()->json(['success' => false, 'message' => 'Tidak ada antrian.'], 404);

            if (!str_starts_with($antrian->barcode_code, 'data:image')) {
                $antrian->barcode_code = 'data:image/png;base64,' . $antrian->barcode_code;
            }

            $this->markIfPast($antrian);

            return response()->json([
                'success' => true,
                'data' => $antrian,
                'barcode_image' => $antrian->barcode_code,
                'kode' => 'ANTRI-'.$antrian->pasien_id.'-'.$antrian->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal ambil antrian terakhir: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.', 'error' => $e->getMessage()], 500);
        }
    }

    public function riwayat()
    {
        try {
            $pasienId = auth()->id();
            if (!$pasienId) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

            $riwayat = Antrian::where('pasien_id', $pasienId)->orderByDesc('created_at')->get();

            foreach ($riwayat as $item) {
                if ($item->barcode_code && !str_starts_with($item->barcode_code, 'data:image')) {
                    $item->barcode_code = 'data:image/png;base64,' . $item->barcode_code;
                }
                $this->markIfPast($item);
            }

            return response()->json(['success' => true, 'data' => $riwayat]);
        } catch (\Exception $e) {
            Log::error('Gagal ambil riwayat antrian: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil riwayat.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getKuotaPerJam(Request $request)
    {
        try {
            $validated = $request->validate([
                'poli'    => 'required|string|max:100',
                'tanggal' => 'required|date',
            ]);

            $kuotaPerJam = 6;
            $jamOperasional = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];

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
            return response()->json(['success' => false, 'message' => 'Gagal ambil data kuota.', 'error' => $e->getMessage()], 500);
        }
    }

    public function batalkan($id)
    {
        try {
            $antrian = Antrian::find($id);
            if (!$antrian) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);

            if (!in_array($antrian->status, ['antri','menunggu'])) {
                return response()->json(['success' => false, 'message' => 'Antrian tidak bisa dibatalkan.'], 400);
            }

            $antrian->status = 'dibatalkan';
            $antrian->save();

            return response()->json(['success' => true, 'message' => 'Antrian berhasil dibatalkan.', 'data' => $antrian]);
        } catch (\Exception $e) {
            Log::error('Gagal membatalkan antrian: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat membatalkan antrian.', 'error' => $e->getMessage()], 500);
        }
    }

    private function markIfPast(Antrian $antrian): void
    {
        try {
            if (!in_array($antrian->status, ['antri','menunggu','dipanggil'])) return;
            if (!$antrian->tanggal || !$antrian->jam) return;

            $waktu = Carbon::parse("{$antrian->tanggal} {$antrian->jam}");
            if ($waktu->lt(Carbon::now())) {
                $antrian->status = 'terlewat';
            }
        } catch (\Exception $e) {
            Log::error('Gagal tandai antrian terlewat: ' . $e->getMessage());
        }
    }
}
