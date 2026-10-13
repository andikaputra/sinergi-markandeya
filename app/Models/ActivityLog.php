<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['changes' => 'array', 'occurred_at' => 'datetime'];

    public const MODULES = [
        'bimbingan' => 'Bimbingan', 'monev' => 'Monev', 'penilaian' => 'Penilaian',
        'penugasan' => 'Penugasan Dosen / Pembimbing', 'program_kerja' => 'Program Kerja', 'luaran' => 'Luaran',
        'jurnal' => 'Jurnal', 'publikasi' => 'Publikasi', 'penempatan' => 'Penempatan', 'kegiatan' => 'Pendaftaran Kegiatan',
        'akun' => 'Akun', 'periode' => 'Tahun Akademik', 'pengumuman' => 'Pengumuman', 'lokasi' => 'Lokasi',
        'notifikasi' => 'Notifikasi', 'authentication' => 'Login / Logout', 'laporan' => 'Laporan',
    ];

    public const ACTIONS = ['created' => 'Buat data', 'updated' => 'Ubah data', 'deleted' => 'Hapus data',
        'login' => 'Login berhasil', 'logout' => 'Logout', 'authenticated_access' => 'Akses akun terautentikasi',
        'viewed' => 'Buka halaman', 'report_opened' => 'Buka laporan cetak', 'download_prepared' => 'Siapkan unduhan',
        'export_prepared' => 'Siapkan ekspor', 'processed' => 'Permintaan diproses'];

    public function getActionLabelAttribute(): string
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }
}
