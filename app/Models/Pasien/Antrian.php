<?php

namespace App\Models\Pasien; // ✅ Sesuaikan dengan folder yang benar

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Untuk relasi ke pengguna

class Antrian extends Model
{
    protected $table = 'tbl_antrian';

    protected $fillable = [
        'pasien_id',         // ✅ Ganti dari 'user_id' ke 'pasien_id'
        'poli',
        'tanggal',
        'jam',
        'status',
        'nomor_antrian',
        'barcode_code', // ✅ Tambahan untuk menyimpan kode barcode
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
