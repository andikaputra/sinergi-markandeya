<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiPpl extends Model
{
    protected $table = 'lokasi_ppl';
    protected $fillable = ['Sekolah', 'alamat', 'maks_peserta'];

    public function jumlahPendaftar(?string $tahunAkademik = null): int
    {
        return \App\Models\MahasiswaKegiatan::where('kegiatan', 'PPL')
            ->where('preferensi_lokasi_id', $this->id)
            ->where('status_kegiatan', 'aktif')
            ->when($tahunAkademik !== null, fn ($query) => $query->where('tahun_akademik', $tahunAkademik))
            ->count();
    }

    public function isFull(?string $tahunAkademik = null): bool
    {
        return $this->maks_peserta !== null && $this->jumlahPendaftar($tahunAkademik) >= $this->maks_peserta;
    }


    public function penempatanppl()
    {
        return $this->hasMany(PenempatanPpl::class, 'sekolah_id', 'id');
    }

    public function dosenMonev()
    {
        return $this->hasOne(DosenMonev::class, 'lokasi_id', 'id')->where('monev_type', 'kelompok');
    }
}
