@extends('layouts.report', ['reportTitle' => 'Laporan Rekapitulasi Bimbingan', 'landscape' => true])
@section('report_period')Tahun akademik: {{ request('ta') ?: 'Semua' }} · Kegiatan: {{ request('kegiatan') ?: 'Semua sesuai akses' }} · Status: {{ request('status') ? str_replace('_', ' ', request('status')) : 'Semua' }} · {{ $bimbingans->count() }} permohonan
@if(request('tanggal_mulai') || request('tanggal_selesai'))<br>Tanggal bimbingan: {{ request('tanggal_mulai') ?: 'Awal' }} — {{ request('tanggal_selesai') ?: 'Terakhir' }}
@endif


@endsection

@section('report_content')
<div class="report-scroll"><table class="report-table"><thead><tr><th style="width: 4%">No</th><th>Mahasiswa / Kegiatan</th><th>Dosen Pembimbing</th><th>Topik / Materi</th><th>Catatan Pembimbing</th><th style="width: 10%">Tanggal</th><th style="width: 12%">Status</th></tr></thead><tbody>
@forelse($bimbingans as $review)<tr><td>{{ $loop->iteration }}</td><td>{{ $review->mahasiswa?->nama ?? $review->nim }}<small>{{ $review->nim }} · {{ $review->mahasiswa?->kegiatan ?? 'Belum diatur' }}</small></td><td>{{ $review->dosenPembimbing?->dosen?->nama ?? 'Belum ditetapkan' }}</td><td><strong>{{ $review->topik }}</strong><br>{{ $review->deskripsi }}</td><td>{{ $review->catatan_dosen ?? '—' }}</td><td>{{ $review->tanggal_bimbingan?->format('d/m/Y') ?? '—' }}</td><td>{{ match($review->status) { 'disetujui' => 'Disetujui', 'perlu_revisi' => 'Perlu revisi', default => 'Belum direview' } }}</td></tr>

@empty
<tr><td colspan="7">Tidak ada bimbingan sesuai filter.</td></tr>
@endforelse

</tbody></table></div><p class="report-note">Persetujuan sesi tercatat di sistem dan tidak menggantikan paraf atau tanda tangan dosen.</p>

@endsection

