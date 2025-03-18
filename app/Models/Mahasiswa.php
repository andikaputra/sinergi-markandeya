<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Mahasiswa extends Authenticatable
{
    use HasFactory;
    protected $guard = 'mahasiswa';

    protected $fillable = [
        'nama',
        'nim',
        'kampus',
        'kegiatan',
        'kecamatan',
        'prodi',
        'pembayaranKRS',
        'KRS',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getProdiFullAttribute()
    {
        $listProdi = [
            'PGSD' => 'S1 Pendidikan Guru Sekolah Dasar',
            'PBSI' => 'S1 Pendidikan Bahasa dan Sastra Indonesia',
            'PBI' => 'S1 Pendidikan Bahasa Inggris',
            'SI' => 'S1 Sistem Informasi',
            'ME' => 'S1 Manajemen Ekonomi',
            'PARBUD' => 'S1 Pariwisata Budaya Dan Keagamaan',
            'HUKUM' => 'S1 Hukum Adat',
        ];
    
        return $listProdi[$this->prodi] ?? $this->prodi;
    }

    public function jurnals()
{
    return $this->hasMany(Jurnal::class, 'nim', 'nim');
}


public function dosenPembimbing()
{
    return $this->hasMany(DosenPembimbing::class, 'nim', 'nim');
}


public function penempatankkn()
{
    return $this->hasOne(Penempatankkn::class, 'nim', 'nim');
}

// Relasi ke tabel LokasiKKN melalui PenempatanKKN
public function lokasikkn()
{
    return $this->hasOne(Penempatankkn::class, 'nim', 'nim');
}

public function penempatanppl()
{
    return $this->hasOne(Penempatanppl::class, 'nim', 'nim');
}

// Relasi ke tabel LokasiKKN melalui PenempatanKKN
public function lokasippl()
{
    return $this->hasOne(Penempatanppl::class, 'nim', 'nim');
}

}
