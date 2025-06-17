<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiUmum extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_umum';

    protected $fillable = [
        'jam_buka',
        'jam_tutup',
        'kuota_antrian',
    ];
}
