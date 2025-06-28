<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // agar petugas bisa login
use Illuminate\Support\Str;

class Petugas extends Authenticatable
{
    use HasFactory;

    // Nama tabel
    protected $table = 'petugas';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'akses_poli', // akan menyimpan array nama poli
    ];

    // Cast ke array agar JSON disimpan sebagai array
    protected $casts = [
        'akses_poli' => 'array',
    ];

    // Hidden untuk keamanan
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Otomatis hash password jika belum terhash
    public function setPasswordAttribute($value)
    {
        if (Str::startsWith($value, '$2y$')) {
            $this->attributes['password'] = $value; // sudah terenkripsi
        } else {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // Optional: relasi jika menggunakan tabel pivot (jika nanti ingin relasi lebih kompleks)
    public function polis()
    {
        return $this->belongsToMany(Poli::class, 'petugas_poli', 'petugas_id', 'poli_id');
    }
}
