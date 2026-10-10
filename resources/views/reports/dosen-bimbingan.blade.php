@extends('layouts.report', ['reportTitle' => 'Rekap Mahasiswa Bimbingan', 'landscape' => true])
@section('report_period')Dosen: {{ Auth::guard('dosen')->user()->nama }} · Tahun akademik: {{ $selectedTA ?: 'Semua' }} · Kegiatan: {{ $selectedKegiatan ?: 'Semua' }}
@endsection

@section('report_content')
<table class="report-table"><thead><tr><th style="width: 5%">No</th><th>NIM</th><th>Mahasiswa</th><th>Kegiatan / Periode</th><th>Nilai Bimbingan</th></tr></thead><tbody>@forelse($mahasiswaBimbingan as $assignment)<tr><td>{{ $loop->iteration }}</td><td>{{ $assignment->nim }}</td><td>{{ $assignment->mahasiswa?->nama ?? '—' }}</td><td>{{ $assignment->mahasiswa?->kegiatan ?? '—' }}<small>{{ $assignment->mahasiswa?->tahun_akademik ?? '—' }}</small></td><td>{{ $assignment->nilai ?? 'Belum dinilai' }}</td></tr>
@empty
<tr><td colspan="5">Tidak ada mahasiswa sesuai filter.</td></tr>
@endforelse
</tbody></table>

@endsection

