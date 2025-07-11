<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Poli; // import model Poli

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'email',
        'no_hp',
        'role',
        'status',
        'password',
        'username', // hanya jika tabel users memiliki kolom username
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi many-to-many ke tabel Poli
     * Gunakan nama "polis" sebagai nama relasi yang baru
     */
    public function polis()
    {
        return $this->belongsToMany(Poli::class, 'user_poli', 'user_id', 'poli_id');
    }
}
 