<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class PembimbingLuar extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'pembimbing_luar';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'instansi',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function mahasiswaBimbingan()
    {
        return $this->hasMany(PembimbingLuarMahasiswa::class);
    }
}
