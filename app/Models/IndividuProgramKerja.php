<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndividuProgramKerja extends Model
{
    protected $table = 'individu_program_kerjas';

    protected $fillable = [
        'nim',
        'kategori',
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function luarans()
    {
        return $this->hasMany(IndividuLuaran::class, 'individu_program_kerja_id');
    }

    public function dosenMonev()
    {
        return $this->hasOne(DosenMonev::class, 'program_id')->where('monev_type', 'individu');
    }

    public function getMonevAttribute()
    {
        if ($this->relationLoaded('dosenMonev') && $this->dosenMonev) {
            return $this->dosenMonev;
        }

        return DosenMonev::where('monev_type', 'individu')
            ->where(function ($q) {
                $q->where('program_id', $this->id)
                  ->orWhere('nim', $this->nim);
            })
            ->first();
    }
}
