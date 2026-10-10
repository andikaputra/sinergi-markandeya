@extends('layouts.report', ['reportTitle'=>'Log Aktivitas Sistem', 'landscape'=>true])
@section('report_period'){{ $logs->count() }} catatan · Modul: {{ \App\Models\ActivityLog::MODULES[request('module')] ?? 'Semua' }} · {{ request('tanggal_mulai') ?: 'Awal' }} — {{ request('tanggal_selesai') ?: 'Terakhir' }}
@endsection

@section('report_content')
<table class="report-table"><thead><tr><th>Waktu</th><th>Pelaku</th><th>Modul / Kegiatan</th><th>Tindakan / Objek</th><th>Perubahan (cuplikan)</th></tr></thead><tbody>
@forelse($logs as $log)
<tr><td>{{ $log->occurred_at->format('d/m/Y H:i:s') }}</td><td>{{ $log->actor_name }}<small>{{ $log->actor_type }} · {{ $log->actor_identifier }}</small></td><td>{{ \App\Models\ActivityLog::MODULES[$log->module] ?? $log->module }}<small>{{ $log->activity }}</small></td><td>{{ $log->action_label }}<small>{{ $log->subject_label }}</small><small>Referensi: {{ substr($log->request_id,0,8) }}</small></td><td>{{ $log->changes ? json_encode($log->changes, JSON_UNESCAPED_UNICODE) : '—' }}</td></tr>

@empty
<tr><td colspan="5">Tidak ada catatan sesuai filter.</td></tr>
@endforelse

</tbody></table><p class="report-note">Riwayat mencatat kejadian yang diamati sistem setelah fitur diterapkan. Password, token, serta isi tautan berkas tidak disalin ke log.</p>

@endsection

