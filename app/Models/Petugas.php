<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // agar petugas bisa login
use Illuminate\Support\Str;

class Petugas extends Authenticatable
{
    use HasFactory;

    // Nama tabel harus sesuai migration
    protected $table = 'petugas';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'akses_poli',
    ];

    protected $casts = [
        'akses_poli' => 'array',
    ];

    protected $hidden = [
        'password',
        'remember_token', // pastikan kolom ini ada di tabel jika dipakai
    ];

    // Mutator untuk meng-hash password otomatis, tapi hindari hash ulang jika sudah terenkripsi
    public function setPasswordAttribute($value)
    {
        if (Str::startsWith($value, '$2y$')) {
            $this->attributes['password'] = $value; // sudah hashed
        } else {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // Relasi many-to-many dengan poli (opsional, sesuaikan nama tabel pivot)
    public function polis()
    {
        return $this->belongsToMany(Poli::class, 'petugas_poli', 'petugas_id', 'poli_id');
    }
}
