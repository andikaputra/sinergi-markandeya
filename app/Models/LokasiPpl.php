<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiPpl extends Model
{
    protected $table = 'lokasi_ppl';
    protected $fillable = ['Sekolah', 'maks_peserta'];

    public function jumlahPendaftar(): int
    {
        return \App\Models\MahasiswaKegiatan::where('kegiatan', 'PPL')
            ->where('preferensi_lokasi_id', $this->id)
            ->where('status_kegiatan', 'aktif')
            ->count();
    }

    public function isFull(): bool
    {
        return $this->maks_peserta !== null && $this->jumlahPendaftar() >= $this->maks_peserta;
    }


    public function penempatanppl()
    {
        return $this->hasMany(PenempatanPpl::class, 'sekolah_id', 'id');
    }
}
