<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $guard = 'mahasiswa';

    protected $fillable = [
        'nama',
        'nim',
        'kampus',
        'kegiatan',
        'laporan_link',
        'tahun_akademik',
        'kecamatan',
        'prodi',
        'pembayaranKRS',
        'KRS',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // ==================== KEGIATAN PIVOT ====================

    public function mahasiswaKegiatan()
    {
        return $this->hasMany(MahasiswaKegiatan::class, 'nim', 'nim');
    }

    public function activeKegiatan()
    {
        return $this->hasOne(MahasiswaKegiatan::class, 'nim', 'nim')->where('is_active', true);
    }

    /**
     * Accessor: $mahasiswa->kegiatan tetap bekerja via pivot
     * Jika ada record aktif di pivot, gunakan itu. Kalau tidak, fallback ke kolom lama.
     */
    public function getKegiatanAttribute($value)
    {
        // Jika relasi sudah di-load, gunakan itu
        if ($this->relationLoaded('activeKegiatan') && $this->activeKegiatan) {
            return $this->activeKegiatan->kegiatan;
        }

        // Kalau belum di-load, cek pivot langsung
        $active = $this->activeKegiatan()->first();
        if ($active) {
            return $active->kegiatan;
        }

        // Fallback ke kolom lama (backward compat)
        return $value;
    }

    /**
     * Accessor: $mahasiswa->tahun_akademik tetap bekerja via pivot
     */
    public function getTahunAkademikAttribute($value)
    {
        if ($this->relationLoaded('activeKegiatan') && $this->activeKegiatan) {
            return $this->activeKegiatan->tahun_akademik;
        }

        $active = $this->activeKegiatan()->first();
        if ($active) {
            return $active->tahun_akademik;
        }

        return $value;
    }

    /**
     * Scope: Mahasiswa::withKegiatan('KKN')
     * Mengganti Mahasiswa::where('kegiatan', 'KKN')
     */
    public function scopeWithKegiatan($query, $kegiatan)
    {
        return $query->whereHas('mahasiswaKegiatan', fn($q) =>
            $q->where('kegiatan', $kegiatan)->where('is_active', true)
        );
    }

    /**
     * Scope: Mahasiswa::withKegiatanIn(['KKN', 'PPL'])
     */
    public function scopeWithKegiatanIn($query, array $kegiatanList)
    {
        return $query->whereHas('mahasiswaKegiatan', fn($q) =>
            $q->whereIn('kegiatan', $kegiatanList)->where('is_active', true)
        );
    }

    /**
     * Scope: Filter berdasarkan tahun akademik di pivot
     */
    public function scopeWithTahunAkademik($query, $ta)
    {
        return $query->whereHas('mahasiswaKegiatan', fn($q) =>
            $q->where('tahun_akademik', $ta)->where('is_active', true)
        );
    }

    /**
     * Scope: Filter kegiatan DAN tahun akademik
     */
    public function scopeWithKegiatanAndTA($query, $kegiatan, $ta = null)
    {
        return $query->whereHas('mahasiswaKegiatan', function($q) use ($kegiatan, $ta) {
            $q->where('kegiatan', $kegiatan)->where('is_active', true);
            if ($ta) {
                $q->where('tahun_akademik', $ta);
            }
        });
    }

    /**
     * Helper: Tambah kegiatan baru untuk mahasiswa ini
     */
    public function addKegiatan($kegiatan, $tahunAkademik = null)
    {
        // Nonaktifkan semua kegiatan aktif sebelumnya
        $this->mahasiswaKegiatan()->where('is_active', true)->update(['is_active' => false]);

        // Buat kegiatan baru sebagai aktif
        return MahasiswaKegiatan::create([
            'nim' => $this->nim,
            'kegiatan' => $kegiatan,
            'tahun_akademik' => $tahunAkademik,
            'is_active' => true,
        ]);
    }

    /**
     * Helper: Daftar semua kegiatan mahasiswa ini
     */
    public function kegiatanList()
    {
        return $this->mahasiswaKegiatan()->get();
    }

    // ==================== EXISTING ACCESSORS ====================

    public function getProdiFullAttribute()
    {
        $listProdi = [
            'PGSD' => 'S1 Pendidikan Guru Sekolah Dasar',
            'PBSI' => 'S1 Pendidikan Bahasa dan Sastra Indonesia',
            'PBI' => 'S1 Pendidikan Bahasa Inggris',
            'SI' => 'S1 Sistem Informasi',
            'ME' => 'S1 Manajemen Ekonomi',
            'PARBUD' => 'S1 Pariwisata Budaya Dan Keagamaan',
            'HUKUM' => 'S1 Hukum Adat',
        ];

        return $listProdi[$this->prodi] ?? $this->prodi;
    }

    public function getNilaiAkhirAttribute()
    {
        $nilaiPembimbing = $this->dosenPembimbing?->nilai;
        $nilaiPenguji = $this->dosenPenguji?->nilai;
        $nilaiPembimbingLuar = $this->pembimbingLuarMahasiswa?->nilai;

        $nilaiList = array_filter([
            $nilaiPembimbing,
            $nilaiPenguji,
            $nilaiPembimbingLuar,
        ], fn($v) => is_numeric($v));

        if (count($nilaiList) >= 2) {
            $rata = array_sum($nilaiList) / count($nilaiList);

            if ($rata >= 85) return "A";
            if ($rata >= 70) return "B";
            if ($rata >= 55) return "C";
            return "D";
        }

        return $nilaiPembimbing ?? $nilaiPenguji ?? $nilaiPembimbingLuar ?? '-';
    }

    // ==================== RELATIONSHIPS ====================

    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'nim', 'nim');
    }

    public function publikasis()
    {
        return $this->hasMany(Publikasi::class, 'nim', 'nim');
    }

    public function penempatankkn()
    {
        return $this->hasOne(PenempatanKkn::class, 'nim', 'nim');
    }

    public function penempatanppl()
    {
        return $this->hasOne(PenempatanPpl::class, 'nim', 'nim');
    }

    public function penempatanpkl()
    {
        return $this->hasOne(PenempatanPkl::class, 'nim', 'nim');
    }

    public function penempatanmagang()
    {
        return $this->hasOne(PenempatanMagang::class, 'nim', 'nim');
    }

    public function dosenPenguji()
    {
        return $this->hasOne(DosenPenguji::class, 'nim', 'nim');
    }

    public function dosenPembimbing()
    {
        return $this->hasOne(DosenPembimbing::class, 'nim', 'nim');
    }

    public function pembimbingLuarMahasiswa()
    {
        return $this->hasOne(PembimbingLuarMahasiswa::class, 'nim', 'nim');
    }

    public function dosenPenilaiPublikasi()
    {
        return $this->hasOne(DosenPenilaiPublikasi::class, 'nim', 'nim');
    }
}
