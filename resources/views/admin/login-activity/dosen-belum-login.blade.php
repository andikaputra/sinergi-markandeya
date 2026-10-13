@extends('layouts.admin')
@section('title', 'Dosen Tanpa Login Tercatat')
@section('content')
<div class="space-y-6"><section class="dash-card"><div class="dash-card-heading"><div><h2>Dosen yang belum memiliki login tercatat</h2><p>Daftar berdasarkan kolom aktivitas login yang masih kosong, bukan pembuktian bahwa dosen tidak pernah mengakses sistem.</p></div><a class="dash-button bg-primary-600 text-white" href="{{ route('admin.login-activity.dosen-belum-login', array_merge(request()->query(), ['cetak'=>1])) }}" target="_blank" rel="noopener noreferrer">Cetak seluruh hasil / PDF</a></div><form class="monev-filter" method="GET"><label>Cari dosen<input type="text" name="q" value="{{ request('q') }}" placeholder="Nama, NIDN, atau NIP"></label><button class="dash-button bg-primary-600 text-white">Tampilkan</button><a class="dash-link" href="{{ route('admin.login-activity.dosen-belum-login') }}">Reset</a></form><p class="monev-note">Pencatatan sekarang dilakukan saat login berhasil dan saat akun mengakses sistem. Riwayat lama yang sebelumnya tidak direkam tetap tidak dapat disimpulkan dari kolom kosong.</p></section><section class="dash-card"><div class="dash-card-heading"><h2>Hasil pencarian</h2><span class="dash-subtle-chip">{{ $dosen->total() }} dosen</span></div><div class="overflow-x-auto"><table class="monev-table"><thead><tr><th>No</th><th>Dosen</th><th>NIDN</th><th>NIP</th><th>Status pencatatan</th><th>Akun dibuat</th></tr></thead><tbody>@forelse($dosen as $lecturer)<tr><td>{{ $dosen->firstItem()+$loop->index }}</td><td>{{ $lecturer->nama }}</td><td>{{ $lecturer->nidn }}</td><td>{{ $lecturer->nip ?? '—' }}</td><td>Belum ada login tercatat</td><td>{{ $lecturer->created_at?->format('d/m/Y') ?? '—' }}</td></tr>
@empty
<tr><td colspan="6">Tidak ada dosen sesuai filter.</td></tr>
@endforelse
</tbody></table></div>{{ $dosen->links() }}</section></div>

@endsection

