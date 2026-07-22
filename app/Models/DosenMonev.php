<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenMonev extends Model
{
    protected $fillable = ['nidn', 'monev_type', 'program_id', 'nilai', 'catatan'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn', 'nidn');
    }

    public function programKerja()
    {
        if ($this->monev_type === 'individu') {
            return $this->belongsTo(IndividuProgramKerja::class, 'program_id');
        }
        return $this->belongsTo(KelompokProgramKerja::class, 'program_id');
    }
}
