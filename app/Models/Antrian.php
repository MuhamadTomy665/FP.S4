<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pasien;
use App\Models\Poli;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'tbl_antrian';

    protected $fillable = [
        'pasien_id',
        'poli',
        'tanggal',
        'jam',
        'status',
        'nomor_antrian',
        'barcode_code',
    ];

    /**
     * Relasi ke tabel pasien
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    /**
     * Relasi ke tabel poli berdasarkan nama_poli (karena kolom 'poli' menyimpan string nama)
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'poli', 'nama_poli');
    }
}
