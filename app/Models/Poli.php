<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    protected $table = 'tbl_poli';

    protected $fillable = [
        'nama_poli',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'dokter',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_poli', 'poli_id', 'user_id');
    }

    public function petugas()
    {
        return $this->belongsToMany(Petugas::class, 'user_poli', 'poli_id', 'user_id');
    }

    // ✅ Relasi ke antrian berdasarkan nama_poli
    public function antrian()
    {
        return $this->hasMany(\App\Models\Antrian::class, 'poli', 'nama_poli');
    }
}
