<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = ['nim', 'judul', 'isi', 'tipe', 'is_read'];
    protected $casts = ['is_read' => 'boolean'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public static function kirim(string $nim, string $judul, string $isi, string $tipe = 'info'): void
    {
        static::create(compact('nim', 'judul', 'isi', 'tipe'));
    }

    public static function jumlahBelumDibaca(string $nim): int
    {
        return static::where('nim', $nim)->where('is_read', false)->count();
    }
}
