<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens; // ✅ tambahkan ini

class Pasien extends Model
{
    use HasApiTokens, HasFactory; // ✅ gunakan trait-nya

    protected $table = 'tbl_pasien';

    protected $fillable = [
        'name',
        'nik',
        'no_hp',
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pasien) {
            $pasien->password = Hash::make($pasien->password);
        });
    }
}
