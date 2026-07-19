<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiMagang extends Model
{
    use HasFactory;

    protected $table = 'lokasi_magangs';
    protected $fillable = ['nama_instansi', 'alamat', 'kontak', 'maks_peserta'];

    public function jumlahPendaftar(): int
    {
        return $this->penempatanmagang()->count();
    }

    public function isFull(): bool
    {
        return $this->maks_peserta !== null && $this->jumlahPendaftar() >= $this->maks_peserta;
    }

    public function penempatanmagang()
    {
        return $this->hasMany(PenempatanMagang::class, 'lokasi_magang_id', 'id');
    }
}
