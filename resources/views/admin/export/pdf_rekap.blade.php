@extends('layouts.report', ['reportTitle' => $title, 'landscape' => true])
@section('report_period')Tahun akademik: {{ $tahunAkademik }} · {{ $peserta->count() }} peserta
@endsection

@section('report_content')
<div class="report-scroll"><table class="report-table"><thead><tr><th style="width: 4%">No</th><th style="width: 17%">Mahasiswa</th><th>Prodi / Periode</th><th>Lokasi</th><th>Dosen Pembimbing / Nilai</th><th>Dosen Penguji / Nilai</th><th>Dosen Monev</th><th>Nilai Akhir</th></tr></thead><tbody>
@forelse($peserta as $mhs)
<tr><td>{{ $loop->iteration }}</td><td><strong>{{ $mhs->nama }}</strong><small>{{ $mhs->nim }}</small></td><td>{{ $mhs->prodi_full ?? $mhs->prodi ?? '—' }}<small>{{ $mhs->tahun_akademik ?? 'Belum diatur' }}</small></td><td>{{ match ($kegiatan) { 'KKN' => $mhs->penempatankkn?->lokasikkn?->desa, 'PPL' => $mhs->penempatanppl?->lokasippl?->Sekolah, 'PKL' => $mhs->penempatanpkl?->lokasipkl?->nama_instansi, default => $mhs->penempatanmagang?->lokasimagang?->nama_instansi } ?? 'Belum ditempatkan' }}</td><td>{{ $mhs->dosenPembimbing?->dosen?->nama ?? 'Belum ditetapkan' }}<small>Nilai: {{ $mhs->dosenPembimbing?->nilai ?? 'Belum dinilai' }}</small></td><td>{{ $mhs->dosenPenguji?->dosen?->nama ?? 'Belum ditetapkan' }}<small>Nilai: {{ $mhs->dosenPenguji?->nilai ?? 'Belum dinilai' }}</small></td><td>{{ $mhs->dosen_monev_model?->dosen?->nama ?? 'Belum ditetapkan' }}</td><td>{{ $mhs->nilai_akhir === '-' ? 'Belum tersedia' : $mhs->nilai_akhir }}</td></tr>

@empty
<tr><td colspan="8">Tidak ada peserta sesuai filter tahun akademik.</td></tr>
@endforelse

</tbody></table></div><p class="report-note">DP: dosen pembimbing. DU: dosen penguji. Nilai yang belum tersedia tidak dihitung sebagai nol.</p><div class="report-signatures"><div></div><div class="report-signature"><p>{{ now()->translatedFormat('d F Y') }}<br>Kepala Pusat LPPM,</p><div class="report-signature-space"></div><p>____________________________<br>NIDN: _______________________</p></div></div>

@endsection

