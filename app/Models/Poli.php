<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    // Tentukan nama tabel
    protected $table = 'tbl_poli';

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'nama_poli',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'dokter',
    ];

    // Kolom yang disembunyikan saat response JSON
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Relasi ke model User (jika user bisa memilih poli)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_poli', 'poli_id', 'user_id');
    }

    /**
     * Relasi ke model Petugas (jika petugas terkait poli)
     * Pastikan model Petugas memang ada
     */
    public function petugas()
    {
        return $this->belongsToMany(Petugas::class, 'user_poli', 'poli_id', 'user_id');
    }
}
