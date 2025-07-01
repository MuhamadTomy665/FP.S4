<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ ganti ke Authenticatable
use Laravel\Sanctum\HasApiTokens;

class Pasien extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'tbl_pasien';

    protected $fillable = [
        'name',
        'nik',
        'no_hp',
        'password',
    ];

    protected $hidden = [
        'password', // agar tidak tampil saat `->toArray()` / API response
    ];
} 
