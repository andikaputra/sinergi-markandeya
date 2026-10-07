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
        'nip',
        'nama',
        'foto',
        'ais_token',
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

    public function mahasiswaPublikasi()
    {
        return $this->hasMany(DosenPenilaiPublikasi::class, 'nidn', 'nidn');
    }

    public function getAllNidns(): array
    {
        $nidns = [$this->nidn];
        if (!empty($this->nip)) {
            $nidns[] = $this->nip;
        }

        if (in_array($this->nidn, ['0020097807', '11112304']) || in_array($this->nip, ['0020097807', '11112304'])) {
            $nidns[] = '0020097807';
            $nidns[] = '11112304';
        }

        return array_values(array_unique(array_filter($nidns)));
    }

    public function monevPrograms()
    {
        return $this->hasMany(DosenMonev::class, 'nidn', 'nidn');
    }
}
