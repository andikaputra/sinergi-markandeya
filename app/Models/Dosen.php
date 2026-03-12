<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'dosen';

    protected $fillable = [
        'nidn',
        'nama',
        'password',
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
        return $this->hasMany(DosenPembimbing::class, 'nidn', 'nidn');
    }

    public function mahasiswaUjian()
    {
        return $this->hasMany(DosenPenguji::class, 'nidn', 'nidn');
    }
}
