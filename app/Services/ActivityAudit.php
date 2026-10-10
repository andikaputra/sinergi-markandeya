<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PembimbingLuar;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityAudit
{
    public const MODELS = [
        'Bimbingan' => 'bimbingan', 'DosenMonev' => 'monev', 'DosenMonevTahap' => 'monev',
        'DosenPembimbing' => 'penugasan', 'DosenPenguji' => 'penugasan', 'DosenPenilaiPublikasi' => 'penugasan',
        'PembimbingLuarMahasiswa' => 'penugasan', 'IndividuProgramKerja' => 'program_kerja', 'KelompokProgramKerja' => 'program_kerja',
        'ProgramKerja' => 'program_kerja', 'IndividuLuaran' => 'luaran', 'KelompokLuaran' => 'luaran', 'Luaran' => 'luaran',
        'Jurnal' => 'jurnal', 'Publikasi' => 'publikasi', 'PenempatanKkn' => 'penempatan', 'PenempatanPpl' => 'penempatan',
        'PenempatanPkl' => 'penempatan', 'PenempatanMagang' => 'penempatan', 'MahasiswaKegiatan' => 'kegiatan',
        'PengajuanLokasiPKL' => 'penempatan', 'PengajuanLokasiMagang' => 'penempatan', 'Mahasiswa' => 'akun',
        'Dosen' => 'akun', 'User' => 'akun', 'PembimbingLuar' => 'akun', 'TahunAkademik' => 'periode',
        'Pengumuman' => 'pengumuman', 'Notifikasi' => 'notifikasi', 'LokasiKkn' => 'lokasi', 'LokasiPpl' => 'lokasi', 'LokasiPkl' => 'lokasi', 'LokasiMagang' => 'lokasi',
    ];

    public function actor($user = null): array
    {
        if (! $user && request()->attributes->has('audit_actor')) {
            return request()->attributes->get('audit_actor');
        }
        $user ??= request()->user();
        if (! $user) {
            foreach (['mahasiswa', 'dosen', 'pembimbing_luar', 'web'] as $guard) {
                if ($candidate = auth($guard)->user()) {
                    $user = $candidate;
                    break;
                }
            }
        }
        $type = match (true) {
            $user instanceof User => 'admin', $user instanceof Dosen => 'dosen',
            $user instanceof Mahasiswa => 'mahasiswa', $user instanceof PembimbingLuar => 'pembimbing_luar', default => 'tamu',
        };

        return ['actor_type' => $type, 'actor_id' => $user?->getKey(),
            'actor_name' => $user?->nama ?? $user?->name ?? 'Pengunjung',
            'actor_identifier' => $user?->nim ?? $user?->nidn ?? ($user instanceof PembimbingLuar ? $user->email : null)];
    }

    public function write(string $module, string $action, array $data = [], ?array $actor = null): void
    {
        $request = request();
        if (! $request->attributes->get('audit_enabled')) {
            return;
        }
        $actor ??= $this->actor();
        if ($actor['actor_type'] === 'tamu' && ! isset($data['subject_id'])) {
            return;
        }
        $id = $request->attributes->get('audit_request_id') ?? (string) Str::uuid();
        ActivityLog::create($actor + $data + [
            'request_id' => $id, 'module' => $module, 'action' => $action,
            'route_name' => $request->route()?->getName(), 'occurred_at' => now(),
        ]);
        $request->attributes->set('audit_entries', $request->attributes->get('audit_entries', 0) + 1);
    }

    public function authentication(string $action, $user): void
    {
        if (! $user || ($user instanceof Mahasiswa && $user->status === 'nonaktif')) {
            return;
        }
        $actor = $this->actor($user);
        $key = $action.':'.$actor['actor_type'].':'.$actor['actor_id'];
        $keys = request()->attributes->get('audit_auth_keys', []);
        if (in_array($key, $keys, true)) {
            return;
        }
        request()->attributes->set('audit_auth_keys', [...$keys, $key]);
        $this->write('authentication', $action, [], $actor);
    }

    public function modelChange(Model $model, string $action): void
    {
        if (! request()->attributes->get('audit_enabled')) {
            return;
        }
        $type = class_basename($model);
        $module = self::MODELS[$type] ?? null;
        if (! $module) {
            return;
        }
        $values = $action === 'updated' ? $model->getChanges() : $model->getAttributes();
        $allowed = ['nim', 'nidn', 'nama', 'name', 'role', 'kegiatan', 'kategori', 'tahun_akademik', 'status', 'status_kegiatan', 'is_active', 'is_published',
            'nip', 'email', 'alamat', 'kontak', 'isi', 'tipe', 'desa', 'Sekolah', 'kecamatan', 'kabupaten', 'provinsi', 'nama_instansi', 'kampus', 'prodi', 'bidang_minat', 'skill', 'motivasi', 'preferensi_lokasi_id', 'is_read', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_mulai_daftar', 'tanggal_selesai_daftar', 'link_dokumen_1', 'link_dokumen_2', 'link_dokumen_3', 'topik', 'deskripsi', 'catatan', 'catatan_dosen', 'judul', 'tanggal', 'tanggal_bimbingan', 'tanggal_monev', 'tahap_ke', 'materi_terlampir',
            'laporan_link', 'link_monev', 'foto_monev', 'persentase_selesai', 'nim_ketua', 'program_id', 'lokasi_id', 'lokasi_kkn_id', 'sekolah_id',
            'lokasi_pkl_id', 'lokasi_magang_id', 'dosen_pembimbing_id', 'dosen_monev_id', 'pembimbing_luar_id', 'maks_peserta', 'is_ketua'];
        $changes = [];
        foreach ($values as $field => $value) {
            if (! in_array($field, $allowed, true) && ! str_starts_with($field, 'nilai')) {
                continue;
            }
            $changes[$field] = [
                'sebelum' => $action === 'created' ? null : $this->safeValue($field, $model->getRawOriginal($field)),
                'sesudah' => $action === 'deleted' ? null : $this->safeValue($field, $value),
            ];
        }
        if ($action === 'updated' && array_key_exists('password', $values)) {
            $changes['sandi'] = ['sebelum' => 'Tidak disalin', 'sesudah' => 'Diubah'];
        }
        if (! $changes && $action === 'updated') {
            return;
        }
        if ($module === 'penugasan' && collect(array_keys($changes))->contains(fn ($key) => str_starts_with($key, 'nilai'))) {
            $module = 'penilaian';
        }
        $this->write($module, $action, $this->subjectContext($model) + ['changes' => $changes]);
        request()->attributes->set('audit_model_entries', request()->attributes->get('audit_model_entries', 0) + 1);
    }

    public function subjectContext(Model $model): array
    {
        $type = class_basename($model);
        $source = $type === 'DosenMonevTahap' ? ($model->dosenMonev ?? $model) : $model;
        $attributes = $source->getAttributes();
        $activity = $this->canonicalActivity($attributes['kategori'] ?? $attributes['kegiatan'] ?? null);
        if (str_starts_with($type, 'Penempatan')) {
            $activity = match ($type) {
                'PenempatanKkn' => 'KKN','PenempatanPpl' => 'PPL','PenempatanPkl' => 'PKL',default => 'Magang'
            };
        }
        $nim = $attributes['nim'] ?? $attributes['nim_ketua'] ?? null;
        if (! $activity && $nim) {
            $activity = $this->canonicalActivity(Mahasiswa::where('nim', $nim)->value('kegiatan'));
        }
        $label = $attributes['topik'] ?? $attributes['judul'] ?? $attributes['nama'] ?? $attributes['name'] ?? $nim ?? $model->getKey();
        if ($type === 'DosenMonevTahap') {
            $label = 'Monev '.$model->tahap_ke.' · '.($nim ? 'NIM '.$nim : 'Penugasan #'.$source->getKey());
        } elseif ($nim && (string) $label !== (string) $nim) {
            $label .= ' · NIM '.$nim;
        }

        return ['subject_type' => $type, 'subject_id' => (string) $model->getKey(),
            'subject_label' => Str::limit((string) $label, 200), 'activity' => $activity];
    }

    public function requestContext(): array
    {
        foreach (request()->route()?->parameters() ?? [] as $parameter) {
            if ($parameter instanceof Model && isset(self::MODELS[class_basename($parameter)])) {
                return $this->subjectContext($parameter);
            }
        }
        if ($nim = request()->route('nim')) {
            if ($student = Mahasiswa::where('nim', $nim)->first()) {
                return $this->subjectContext($student);
            }
        }
        if (str_contains(request()->route()?->uri() ?? '', 'program-kerja-monev/detail/{id}')) {
            if ($monev = \App\Models\DosenMonev::find(request()->route('id'))) {
                return $this->subjectContext($monev);
            }
        }
        $activity = $this->canonicalActivity(request()->input('kegiatan'));
        if (! $activity && preg_match('/(kkn|ppl|pkl|magang)(?:[.\/-]|$)/i', (request()->route()?->getName() ?? '').' '.(request()->route()?->uri() ?? ''), $matches)) {
            $activity = $this->canonicalActivity($matches[1]);
        }

        return ['activity' => $activity];
    }

    private function safeValue(string $field, $value)
    {
        if ($value === null) {
            return null;
        }
        if ($field === 'foto_monev') {
            $photos = is_string($value) ? (json_decode($value, true) ?? [$value]) : $value;
            if (! is_array($photos)) {
                $photos = [$photos];
            }

            return ['jumlah_berkas' => count(array_filter($photos, fn ($photo) => is_string($photo) && trim($photo) !== ''))];
        }
        if (str_starts_with($field, 'link_') || $field === 'laporan_link') {
            return trim(is_string($value) ? $value : json_encode($value)) === '' ? 'Kosong' : 'Isian tersedia (isi tautan/berkas tidak disalin)';
        }
        if (is_string($value)) {
            return Str::limit($value, 1000);
        }

        return $value;
    }

    public function canonicalActivity($value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        return match (strtolower($value)) {
            'kkn' => 'KKN','ppl' => 'PPL','pkl' => 'PKL','magang' => 'Magang',default => null
        };
    }

    public function routeModule(): ?string
    {
        $route = request()->route()?->getName() ?? '';
        $uri = request()->route()?->uri() ?? '';
        foreach (['bimbingan' => 'bimbingan', 'monev' => 'monev', 'program-kerja' => 'program_kerja', 'luaran' => 'luaran', 'jurnal' => 'jurnal',
            'publikasi' => 'publikasi', 'lokasi' => 'penempatan', 'daftar-kegiatan' => 'kegiatan', 'switch-kegiatan' => 'kegiatan',
            'kegiatan' => 'kegiatan', 'tahun-akademik' => 'periode', 'notifikasi' => 'notifikasi', 'pengumuman' => 'pengumuman', 'mahasiswa' => 'akun', 'profil' => 'akun', 'dosen' => 'penugasan'] as $key => $module) {
            if (str_contains($route.' '.$uri, $key)) {
                return $module;
            }
        }

        return null;
    }
}
