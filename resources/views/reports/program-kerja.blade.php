@extends('layouts.report', ['reportTitle' => 'Laporan Program Kerja Individu', 'landscape' => true])
@section('report_period'){{ $programs->count() }} program kerja
@endsection

@section('report_content')<table class="report-table"><thead><tr><th style="width: 5%">No</th><th>Mahasiswa</th><th>Kegiatan</th><th>Program Kerja</th><th>Pelaksanaan</th><th>Status</th></tr></thead><tbody>@forelse($programs as $program)<tr><td>{{ $loop->iteration }}</td><td>{{ $program->mahasiswa?->nama ?? $program->nim }}<small>{{ $program->nim }}</small></td><td>{{ strtoupper($program->kategori) }}</td><td>{{ $program->judul }}<small>{{ $program->deskripsi }}</small></td><td>{{ $program->tanggal_mulai?->format('d/m/Y') ?? '—' }} — {{ $program->tanggal_selesai?->format('d/m/Y') ?? '—' }}</td><td>{{ str_replace('_', ' ', $program->status) }}</td></tr>
@empty
<tr><td colspan="6">Belum ada program kerja.</td></tr>
@endforelse
</tbody></table>
@endsection

