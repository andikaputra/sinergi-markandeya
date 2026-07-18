<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndividuLuaran extends Model
{
    protected $table = 'individu_luarans';

    protected $fillable = [
        'individu_program_kerja_id',
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
        return $this->belongsTo(IndividuProgramKerja::class, 'individu_program_kerja_id');
    }
}
