@extends('layouts.report', ['reportTitle' => 'Laporan Luaran Program Kerja Individu', 'landscape' => true])
@section('report_period'){{ $luarans->count() }} luaran
@endsection

@section('report_content')<table class="report-table"><thead><tr><th style="width: 5%">No</th><th>Mahasiswa</th><th>Program Kerja</th><th>Luaran</th><th>Jenis / Penyelesaian</th><th>Status</th></tr></thead><tbody>@forelse($luarans as $luaran)<tr><td>{{ $loop->iteration }}</td><td>{{ $luaran->programKerja?->mahasiswa?->nama ?? '—' }}<small>{{ $luaran->programKerja?->nim ?? '—' }}</small></td><td>{{ $luaran->programKerja?->judul ?? '—' }}</td><td>{{ $luaran->judul }}</td><td>{{ $luaran->tipe ?? '—' }}<small>{{ $luaran->persentase_selesai ?? 0 }}%</small></td><td>{{ str_replace('_', ' ', $luaran->status) }}</td></tr>
@empty
<tr><td colspan="6">Belum ada luaran.</td></tr>
@endforelse
</tbody></table>
@endsection

