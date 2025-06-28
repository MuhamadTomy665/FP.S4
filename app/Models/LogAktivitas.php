<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Petugas;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'deskripsi',
        'ip_address',
    ];

    /**
     * Relasi ke tabel petugas (karena hanya memantau petugas)
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'user_id');
    }

    /**
     * Accessor untuk mendapatkan nama petugas
     */
    public function getNamaPenggunaAttribute()
    {
        return $this->petugas->nama ?? 'Tidak diketahui';
    }
}
