@extends('layouts.report', ['reportTitle' => 'Laporan Aktivitas Login'])
@section('report_period')Seluruh akun yang pernah tercatat aktif pada sistem
@endsection

@section('report_content')
@foreach(['Mahasiswa'=>$mahasiswas, 'Dosen'=>$dosens] as $role=>$users)<h2 style="font-size: 12pt">{{ $role }} · {{ $users->count() }} akun</h2><table class="report-table"><thead><tr><th style="width: 6%">No</th><th>NIM / NIDN</th><th>Nama</th><th>Aktivitas Terakhir</th></tr></thead><tbody>@forelse($users as $user)<tr><td>{{ $loop->iteration }}</td><td>{{ $user->nim ?? $user->nidn }}</td><td>{{ $user->nama }}</td><td>{{ $user->last_login?->format('d/m/Y H:i') ?? '—' }}</td></tr>
@empty
<tr><td colspan="4">Belum ada aktivitas.</td></tr>
@endforelse
</tbody></table>
@endforeach

<p class="report-note">Waktu di atas menunjukkan aktivitas terakhir yang tercatat, bukan daftar lengkap setiap sesi login.</p>

@endsection

