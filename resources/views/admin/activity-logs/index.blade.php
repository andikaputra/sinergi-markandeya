@extends('layouts.admin')
@section('title', 'Log Aktivitas Sistem')
@section('content')
<div class="space-y-6">
<section class="dash-card"><div class="dash-card-heading"><div><h2>Riwayat tindakan pengguna</h2><p>Telusuri pengajuan dan review bimbingan, Monev, penilaian, serta perubahan data lainnya.</p></div><a class="dash-button bg-primary-600 text-white" href="{{ route('admin.activity.index', array_merge(request()->query(), ['cetak'=>1])) }}" target="_blank" rel="noopener noreferrer">Cetak seluruh hasil / PDF</a></div>
<form method="GET" action="{{ route('admin.activity.index') }}" class="monev-filter">
<label>Cari pelaku / objek<input type="text" name="q" value="{{ request('q') }}" placeholder="Nama, NIM, NIDN, atau judul"></label>
<label>Modul<select name="module"><option value="">Semua modul</option>@foreach(\App\Models\ActivityLog::MODULES as $value=>$label)<option value="{{ $value }}" @selected(request('module')===$value)>{{ $label }}</option>
@endforeach
</select></label>
<label>Tindakan<select name="action"><option value="">Semua tindakan</option>@foreach(\App\Models\ActivityLog::ACTIONS as $value=>$label)<option value="{{ $value }}" @selected(request('action')===$value)>{{ $label }}</option>
@endforeach
</select></label>
<label>Pelaku<select name="actor_type"><option value="">Semua pelaku</option>@foreach(['admin'=>'Admin','dosen'=>'Dosen','mahasiswa'=>'Mahasiswa','pembimbing_luar'=>'Pembimbing luar','tamu'=>'Pengunjung'] as $value=>$label)<option value="{{ $value }}" @selected(request('actor_type')===$value)>{{ $label }}</option>
@endforeach
</select></label>
<label>Dari tanggal<input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"></label><label>Sampai tanggal<input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"></label><button class="dash-button bg-primary-600 text-white">Tampilkan</button><a class="dash-link" href="{{ route('admin.activity.index') }}">Reset</a>
</form><p class="monev-note">Log mulai dicatat setelah fitur ini diterapkan. “Buka laporan cetak” mencatat pembukaan dokumen, bukan konfirmasi dokumen benar-benar dicetak.</p></section>
<section class="dash-card"><div class="dash-card-heading"><h2>Aktivitas tercatat</h2><span class="dash-subtle-chip">{{ $logs->total() }} catatan sesuai filter</span></div><div class="overflow-x-auto"><table class="monev-table"><thead><tr><th>Waktu</th><th>Pelaku</th><th>Modul / Kegiatan</th><th>Tindakan</th><th>Objek / Perubahan</th></tr></thead><tbody>
@forelse($logs as $log)
<tr><td>{{ $log->occurred_at->format('d/m/Y H:i:s') }}</td><td><strong>{{ $log->actor_name }}</strong><small>{{ ucfirst(str_replace('_',' ',$log->actor_type)) }} · {{ $log->actor_identifier ?? 'ID '.$log->actor_id }}</small></td><td>{{ \App\Models\ActivityLog::MODULES[$log->module] ?? $log->module }}<small>{{ $log->activity ?? 'Lintas kegiatan / tidak ditentukan' }}</small></td><td>{{ $log->action_label }}</td><td><strong>{{ $log->subject_label ?? 'Sesi autentikasi' }}</strong><small>{{ $log->subject_type ? $log->subject_type.' #'.$log->subject_id : '' }}</small>
@if($log->changes)<details class="monev-targets"><summary>Lihat {{ count($log->changes) }} perubahan</summary><pre class="audit-changes">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></details>
@endif

<small>Referensi: {{ substr($log->request_id,0,8) }}</small></td></tr>

@empty
<tr><td colspan="5">Belum ada log sesuai filter. Riwayat baru akan tampil setelah pengguna melakukan aktivitas.</td></tr>
@endforelse

</tbody></table></div>{{ $logs->links() }}</section></div>

@endsection

