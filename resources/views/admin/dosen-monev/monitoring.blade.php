@extends('layouts.admin')
@section('title', 'Monitoring Pelaksanaan Monev')
@section('content')
<div class="space-y-6"><section class="dash-card"><div class="dash-card-heading"><div><h2>Dosen yang perlu ditindaklanjuti</h2><p>Pantau Monev yang tercatat pada penugasan sesuai akses kegiatan Anda.</p></div><a class="dash-button bg-primary-600 text-white" href="{{ route('admin.monev.monitoring', array_merge(request()->query(), ['status'=>$status,'cetak'=>1])) }}" target="_blank" rel="noopener noreferrer">Cetak sesuai filter / PDF</a></div>
<div class="monev-summary"><span>Pemonev ditugaskan <strong>{{ $summary['total'] }}</strong></span><span>Belum mulai sama sekali <strong>{{ $summary['belum_mulai'] }}</strong></span><span>Dalam proses <strong>{{ $summary['berjalan'] }}</strong></span><span>Seluruh tahap tercatat <strong>{{ $summary['lengkap'] }}</strong></span></div>
<form action="{{ route('admin.monev.monitoring') }}" method="GET" class="monev-filter"><label>Cari dosen / penugasan<input type="text" name="q" value="{{ request('q') }}" placeholder="Nama dosen, NIDN, NIP, atau lokasi"></label><label>Kegiatan<select name="kegiatan"><option value="">Semua sesuai akses</option>@foreach($allowed as $activity)<option value="{{ $activity }}" @selected(request('kegiatan')===$activity)>{{ strtoupper($activity) }}</option>
@endforeach
</select></label><label>Status<select name="status">@foreach(['belum_mulai'=>'Belum mencatat Monev sama sekali','tugas_belum'=>'Ada penugasan belum dimonev','berjalan'=>'Tahap Monev belum lengkap','lengkap'=>'Seluruh tahap sudah tercatat','belum_login'=>'Belum ada login tercatat','semua'=>'Semua pemonev'] as $value=>$label)<option value="{{ $value }}" @selected($status===$value)>{{ $label }}</option>
@endforeach
</select></label><button class="dash-button bg-primary-600 text-white">Tampilkan</button><a class="dash-link" href="{{ route('admin.monev.monitoring') }}">Reset</a></form>
<p class="monev-note">Dosen tanpa penugasan tidak termasuk daftar Monev. <a class="dash-link" href="{{ route('admin.login-activity.dosen-belum-login') }}">Lihat semua dosen yang belum ada login tercatat →</a></p></section>
<section class="dash-card"><div class="dash-card-heading"><h2>Daftar dosen pemonev</h2><span class="dash-subtle-chip">{{ $lecturers->total() }} pemonev sesuai filter</span></div><div class="overflow-x-auto"><table class="monev-table"><thead><tr><th>Dosen</th><th>Aktivitas login terakhir</th><th>Total penugasan</th><th>Belum dimonev</th><th>1–2 tahap</th><th>3 tahap</th><th>Monev terakhir</th></tr></thead><tbody>
@forelse($lecturers as $row)
<tr><td><strong>{{ $row['dosen']?->nama ?? 'Akun dosen tidak ditemukan' }}</strong><small>NIDN/identitas: {{ $row['dosen']?->nidn ?? $row['nidn'] }}</small><details class="monev-targets"><summary>Lihat {{ $row['total'] }} penugasan</summary>@foreach($row['assignments'] as $assignment)<div><strong>{{ $assignment['target'] }}</strong><small>{{ strtoupper($assignment['activity'] ?: 'Belum diatur') }} · {{ ucfirst($assignment['type']) }} · {{ $assignment['stage_count'] }}/3 tahap</small><small>Belum tercatat: @foreach($assignment['stages'] as $number=>$recorded)@if(!$recorded)Monev {{ $number }} 
@endif
 
@endforeach
</small></div>
@endforeach
</details></td><td>{{ $row['dosen']?->last_login?->format('d/m/Y H:i') ?? ($row['dosen'] ? 'Belum ada login tercatat' : 'Akun tidak tersedia') }}</td><td>{{ $row['total'] }}</td><td><span class="dash-badge dash-badge-gold">{{ $row['belum'] }}</span></td><td>{{ $row['berjalan'] }}</td><td>{{ $row['lengkap'] }}</td><td>{{ $row['last_monev']?->format('d/m/Y') ?? 'Belum ada tanggal Monev' }}</td></tr>

@empty
<tr><td colspan="7">Tidak ada dosen yang sesuai filter ini.</td></tr>
@endforelse

</tbody></table></div>{{ $lecturers->links() }}<p class="monev-note">Status berdasarkan isian evaluasi, bukan tanggal pembaruan penugasan. Aktivitas yang dilakukan di luar sistem belum dapat terlihat di laporan ini.</p></section></div>

@endsection

