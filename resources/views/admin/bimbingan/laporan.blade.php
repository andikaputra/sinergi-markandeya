@extends('layouts.admin')
@section('title', 'Laporan Rekapitulasi Bimbingan')
@section('content')
<div class="space-y-6"><div class="dash-card"><div class="dash-card-heading"><div><h2>Laporan bimbingan</h2><p>Filter data, lalu cetak seluruh hasil yang sesuai.</p></div><a class="dash-button bg-primary-600 text-white" href="{{ route('admin.bimbingan.laporan', array_merge(request()->query(), ['cetak' => 1])) }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-print" aria-hidden="true"></i>Cetak / Simpan PDF</a></div>
<form method="GET" action="{{ route('admin.bimbingan.laporan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
<label class="text-xs">Tahun akademik<select name="ta" class="block w-full p-2 mt-1"><option value="">Semua tahun akademik</option>@foreach($tahunAkademiks as $period)@php $value = $period->tahun.' '.$period->semester; @endphp<option value="{{ $value }}" @selected(request('ta') === $value)>{{ $value }}</option>
@endforeach
</select></label>
<label class="text-xs">Kegiatan<select name="kegiatan" class="block w-full p-2 mt-1"><option value="">Semua sesuai akses</option>@foreach(Auth::guard('web')->user()->getAllowedKegiatan() as $activity)<option @selected(request('kegiatan') === $activity)>{{ $activity }}</option>
@endforeach
</select></label>
<label class="text-xs">Status<select name="status" class="block w-full p-2 mt-1"><option value="">Semua status</option>@foreach(['belum_direview'=>'Belum direview','perlu_revisi'=>'Perlu revisi','disetujui'=>'Disetujui'] as $value=>$label)<option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
@endforeach
</select></label>
<label class="text-xs">Tanggal mulai<input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="block w-full p-2 mt-1"></label><label class="text-xs">Tanggal selesai<input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="block w-full p-2 mt-1"></label><div class="flex items-end gap-3"><button class="dash-button bg-primary-600 text-white">Terapkan Filter</button><a class="dash-link" href="{{ route('admin.bimbingan.laporan') }}">Reset</a></div>
</form></div><div class="dash-card"><div class="dash-card-heading"><h2>Hasil laporan</h2><span class="dash-subtle-chip">{{ $bimbingans->count() }} permohonan</span></div><div class="overflow-x-auto"><table class="w-full text-left"><thead><tr><th class="p-3">Mahasiswa</th><th class="p-3">Dosen Pembimbing</th><th class="p-3">Topik</th><th class="p-3">Tanggal</th><th class="p-3">Status</th></tr></thead><tbody>@forelse($bimbingans as $review)<tr class="border-b"><td class="p-3">{{ $review->mahasiswa?->nama ?? $review->nim }}<small class="block">{{ $review->nim }}</small></td><td class="p-3">{{ $review->dosenPembimbing?->dosen?->nama ?? '—' }}</td><td class="p-3">{{ $review->topik }}</td><td class="p-3">{{ $review->tanggal_bimbingan?->format('d/m/Y') ?? '—' }}</td><td class="p-3">{{ str_replace('_', ' ', $review->status) }}</td></tr>
@empty
<tr><td class="p-5" colspan="5">Tidak ada data sesuai filter.</td></tr>
@endforelse
</tbody></table></div></div></div>

@endsection

