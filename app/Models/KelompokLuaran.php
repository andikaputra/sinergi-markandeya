<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokLuaran extends Model
{
    protected $table = 'kelompok_luarans';

    protected $fillable = [
        'kelompok_program_kerja_id',
        'judul',
        'deskripsi',
        'tipe',
        'tanggal_selesai',
        'file_path',
        'status',
        'persentase_selesai',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date',
    ];

    public function programKerja()
    {
        return $this->belongsTo(KelompokProgramKerja::class, 'kelompok_program_kerja_id');
    }
}
