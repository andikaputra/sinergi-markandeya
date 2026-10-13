@extends('layouts.dosen')
@section('title', 'Penugasan Monev')
@section('content')
<div class="space-y-6">
    <section class="dash-card"><div class="dash-card-heading"><div><h2>Daftar penugasan Monev</h2><p>Satu baris untuk setiap penugasan. Buka detail untuk mengisi Monev 1, 2, dan 3.</p></div></div>
        <div class="monev-summary"><span>Total penugasan <strong>{{ $totalTugas }}</strong></span><span>Belum mulai <strong>{{ $totalBelum }}</strong></span><span>Sudah mulai <strong>{{ $totalSelesai }}</strong></span><span>Tiga tahap tercatat <strong>{{ $totalLengkap }}</strong></span></div>
        <form method="GET" action="{{ route('dosen.program-kerja.monev-dashboard') }}" class="monev-filter">
            <label>Cari mahasiswa/lokasi<input type="text" name="q" value="{{ request('q') }}" placeholder="Nama, NIM, lokasi, atau program"></label>
            <label>Kegiatan<select name="kegiatan"><option value="">Semua kegiatan</option>@foreach(['kkn','ppl','pkl','magang'] as $activity)<option value="{{ $activity }}" @selected(request('kegiatan')===$activity)>{{ strtoupper($activity) }}</option>
@endforeach
</select></label>
            <label>Status<select name="status">@foreach(['semua'=>'Semua status','belum'=>'Belum mulai','berjalan'=>'Dalam proses','lengkap'=>'Tiga tahap tercatat'] as $value=>$label)<option value="{{ $value }}" @selected(request('status','semua')===$value)>{{ $label }}</option>
@endforeach
</select></label>
            <button class="dash-button bg-primary-600 text-white">Tampilkan</button><a class="dash-link" href="{{ route('dosen.program-kerja.monev-dashboard') }}">Reset</a>
        </form>
    </section>
    @if(session('success'))<div class="dash-message">{{ session('success') }}</div>
@endif

    <section class="dash-card"><div class="dash-card-heading"><h2>Hasil penugasan</h2><span class="dash-subtle-chip">{{ $monevPrograms->total() }} penugasan</span></div><div class="overflow-x-auto"><table class="monev-table"><thead><tr><th>Mahasiswa / Lokasi</th><th>Kegiatan / Jenis</th><th>Monev 1<br><small>Awal</small></th><th>Monev 2<br><small>Progres</small></th><th>Monev 3<br><small>Akhir</small></th><th>Progres</th><th>Aksi</th></tr></thead><tbody>
    @forelse($monevPrograms as $row)
    <tr><td><strong>{{ $row['target'] }}</strong><small>{{ $row['nim'] ? 'NIM '.$row['nim'] : 'Penugasan kelompok' }}</small>@if($row['program'])<small>{{ $row['program'] }}</small>
@endif
</td><td>{{ strtoupper($row['activity'] ?: 'Belum diatur') }}<small>{{ ucfirst($row['type']) }}</small></td>
        @foreach($row['stages'] as $recorded)<td><span class="dash-badge {{ $recorded ? 'dash-badge-green' : 'dash-badge-gold' }}">{{ $recorded ? 'Tercatat' : 'Belum' }}</span></td>
@endforeach

        <td><strong>{{ $row['stage_count'] }}/3 tahap</strong><small>{{ $row['last_monev']?->format('d/m/Y') ?? 'Tanggal belum dicatat' }}</small></td><td><a class="dash-button bg-primary-600 text-white" href="{{ route('dosen.program-kerja.monev-detail-id', $row['id']) }}">{{ $row['stage_count']===0 ? 'Mulai Monev' : 'Buka Monev' }}</a></td></tr>
    
@empty
<tr><td colspan="7">Tidak ada penugasan sesuai filter. Penugasan Monev diatur oleh admin.</td></tr>
@endforelse

    </tbody></table></div>{{ $monevPrograms->links() }}<p class="monev-note">“Tercatat” berarti ada isian evaluasi di sistem. Tiga tahap lengkap berarti Monev 1, 2, dan 3 sudah memiliki isian.</p></section>
</div>

@endsection

