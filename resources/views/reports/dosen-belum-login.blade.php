@extends('layouts.report', ['reportTitle'=>'Dosen Belum Memiliki Login Tercatat'])
@section('report_period'){{ $dosen->count() }} dosen · Pencarian: {{ request('q') ?: 'Semua' }}
@endsection

@section('report_content')
<table class="report-table"><thead><tr><th>No</th><th>Dosen</th><th>NIDN</th><th>NIP</th><th>Status</th></tr></thead><tbody>@forelse($dosen as $lecturer)<tr><td>{{ $loop->iteration }}</td><td>{{ $lecturer->nama }}</td><td>{{ $lecturer->nidn }}</td><td>{{ $lecturer->nip ?? '—' }}</td><td>Belum ada login tercatat</td></tr>
@empty
<tr><td colspan="5">Tidak ada dosen sesuai filter.</td></tr>
@endforelse
</tbody></table><p class="report-note">Kolom aktivitas login masih kosong. Riwayat sebelum pencatatan berjalan dengan benar mungkin tidak tersedia. Seluruh hasil pencarian dicetak, bukan hanya halaman tabel yang sedang dibuka.</p>

@endsection

