<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'barcode_code', // ✅ Perbaikan di sini
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
}
