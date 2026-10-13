@extends('layouts.report', ['reportTitle'=>'Monitoring Pelaksanaan Monev Dosen', 'landscape'=>true])
@section('report_period')Kegiatan: {{ strtoupper(request('kegiatan') ?: 'Semua sesuai akses') }} · Filter: {{ str_replace('_',' ',$status) }} · {{ $lecturers->count() }} pemonev
@endsection

@section('report_content')
<table class="report-table"><thead><tr><th>Dosen / Identitas</th><th>Aktivitas Login Terakhir</th><th>Penugasan</th><th>Kegiatan / Jenis</th><th>Monev 1</th><th>Monev 2</th><th>Monev 3</th><th>Tanggal Monev Terakhir</th></tr></thead><tbody>
@forelse($lecturers as $lecturer)
    @foreach($lecturer['assignments'] as $row)
    <tr><td>{{ $lecturer['dosen']?->nama ?? 'Akun tidak ditemukan' }}<small>{{ $lecturer['dosen']?->nidn ?? $lecturer['nidn'] }}</small></td><td>{{ $lecturer['dosen']?->last_login?->format('d/m/Y H:i') ?? 'Belum tercatat' }}</td><td>{{ $row['target'] }}<small>{{ $row['nim'] }}</small></td><td>{{ strtoupper($row['activity'] ?: 'Belum diatur') }}<small>{{ ucfirst($row['type']) }}</small></td>@foreach($row['stages'] as $recorded)<td>{{ $recorded ? 'Tercatat' : 'Belum' }}</td>
@endforeach
<td>{{ $row['last_monev']?->format('d/m/Y') ?? '—' }}</td></tr>
    
@endforeach


@empty
<tr><td colspan="8">Tidak ada penugasan sesuai filter.</td></tr>
@endforelse

</tbody></table><p class="report-note">Seluruh penugasan dari pemonev yang cocok dengan filter ditampilkan. “Belum” berarti belum ada isian Monev tercatat pada tahap tersebut; kegiatan di luar sistem belum terverifikasi.</p>

@endsection

