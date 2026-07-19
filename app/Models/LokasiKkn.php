<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiKkn extends Model
{

    use HasFactory;
    protected $table = 'lokasi_kkn';
    protected $fillable = ['desa', 'alamat', 'kecamatan', 'kabupaten', 'provinsi', 'maks_peserta'];

    public function jumlahPendaftar(): int
    {
        return \App\Models\MahasiswaKegiatan::where('kegiatan', 'KKN')
            ->where('preferensi_lokasi_id', $this->id)
            ->where('status_kegiatan', 'aktif')
            ->count();
    }

    public function isFull(): bool
    {
        return $this->maks_peserta !== null && $this->jumlahPendaftar() >= $this->maks_peserta;
    }


    public function penempatankkn()
    {
        return $this->hasMany(PenempatanKkn::class, 'lokasi_kkn_id', 'id');
    }

}
