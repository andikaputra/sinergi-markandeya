<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $guard = 'mahasiswa';

    protected $fillable = [
        'nama',
        'nim',
        'kampus',
        'kegiatan',
        'laporan_link',
        'tahun_akademik',
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

    public function getNilaiAkhirAttribute()
    {
        $nilaiPembimbing = $this->dosenPembimbing ? $this->dosenPembimbing->nilai : null;
        $nilaiPenguji = $this->dosenPenguji ? $this->dosenPenguji->nilai : null;

        if (is_numeric($nilaiPembimbing) && is_numeric($nilaiPenguji)) {
            $rata = ($nilaiPembimbing + $nilaiPenguji) / 2;
            
            if ($rata >= 85) return "A";
            if ($rata >= 80) return "A-";
            if ($rata >= 75) return "B+";
            if ($rata >= 70) return "B";
            if ($rata >= 65) return "B-";
            if ($rata >= 60) return "C";
            return "D";
        }

        return $nilaiPembimbing ?? $nilaiPenguji ?? '-';
    }

    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'nim', 'nim');
    }

    public function publikasis()
    {
        return $this->hasMany(Publikasi::class, 'nim', 'nim');
    }

    public function penempatankkn()
    {
        return $this->hasOne(Penempatankkn::class, 'nim', 'nim');
    }

    public function lokasikkn()
    {
        return $this->hasOne(Penempatankkn::class, 'nim', 'nim');
    }

    public function penempatanppl()
    {
        return $this->hasOne(Penempatanppl::class, 'nim', 'nim');
    }

    public function lokasippl()
    {
        return $this->hasOne(Penempatanppl::class, 'nim', 'nim');
    }

    public function penempatanpkl()
    {
        return $this->hasOne(PenempatanPkl::class, 'nim', 'nim');
    }

    public function penempatanmagang()
    {
        return $this->hasOne(PenempatanMagang::class, 'nim', 'nim');
    }

    public function dosenPenguji()
    {
        return $this->hasOne(DosenPenguji::class, 'nim', 'nim');
    }

    public function dosenPembimbing()
    {
        return $this->hasOne(DosenPembimbing::class, 'nim', 'nim');
    }
}
