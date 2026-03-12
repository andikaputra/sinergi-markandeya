@extends('layouts.dosen')

@section('title', 'Mahasiswa Bimbingan')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-6">
            <div class="bg-blue-600 p-5 rounded-2xl shadow-lg shadow-blue-200">
                <i class="fas fa-users text-white text-3xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Mahasiswa Bimbingan</h3>
                <p class="text-gray-500">Total <span class="font-bold text-blue-600">{{ $mahasiswaBimbingan->count() }}</span> mahasiswa bimbingan aktif.</p>
            </div>
        </div>
        @if($selectedTA)
        <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-black border border-blue-100">
            {{ $selectedTA }}
        </span>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('dosen.bimbingan') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun Akademik</label>
                <select name="tahun_akademik" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunAkademiks as $ta)
                        <option value="{{ $ta->tahun }} {{ $ta->semester }}" {{ $selectedTA == $ta->tahun.' '.$ta->semester ? 'selected' : '' }}>
                            {{ $ta->tahun }} {{ $ta->semester }} {{ $ta->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Kegiatan</label>
                <select name="kegiatan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600">
                    <option value="">Semua Kegiatan</option>
                    @foreach(['KKN', 'PPL', 'PKL', 'Magang'] as $k)
                        <option value="{{ $k }}" {{ $selectedKegiatan == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-blue-200">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('dosen.bimbingan') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl text-sm transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Student List -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <h4 class="text-lg font-bold text-gray-800">Daftar Mahasiswa Bimbingan</h4>
            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold border border-blue-100">{{ $mahasiswaBimbingan->count() }} mahasiswa</span>
        </div>

        @if($mahasiswaBimbingan->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">No</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Mahasiswa</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Program & Penempatan</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Publikasi</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Nilai Akhir</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($mahasiswaBimbingan as $i => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-5 text-sm text-gray-400 font-bold">{{ $i + 1 }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs group-hover:scale-110 transition-transform">
                                    {{ substr($item->mahasiswa->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $item->mahasiswa->nama }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $item->mahasiswa->nim }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="space-y-1">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter bg-gray-100 px-2 py-0.5 rounded">
                                    {{ $item->mahasiswa->kegiatan }}
                                </span>
                                <p class="text-xs font-bold text-slate-600">
                                    @if($item->mahasiswa->kegiatan == 'KKN')
                                        Desa {{ $item->mahasiswa->penempatankkn?->lokasikkn?->desa ?? '-' }}
                                    @elseif($item->mahasiswa->kegiatan == 'PPL')
                                        {{ $item->mahasiswa->penempatanppl?->lokasippl?->Sekolah ?? '-' }}
                                    @elseif($item->mahasiswa->kegiatan == 'PKL')
                                        {{ $item->mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                                    @elseif($item->mahasiswa->kegiatan == 'Magang')
                                        {{ $item->mahasiswa->penempatanmagang?->lokasimagang?->nama_instansi ?? '-' }}
                                    @endif
                                </p>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($item->mahasiswa->publikasis->isNotEmpty())
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold border border-emerald-100">
                                    {{ $item->mahasiswa->publikasis->count() }} artikel
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum Ada</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-black border border-emerald-100">
                                {{ $item->mahasiswa->nilai_akhir }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('dosen.mahasiswa.detail', $item->mahasiswa->nim) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-blue-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 shadow-sm">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-16 text-center">
            <div class="w-20 h-20 bg-blue-50 text-blue-300 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Data</h3>
            <p class="text-gray-500 text-sm">Tidak ada mahasiswa bimbingan yang sesuai dengan filter yang dipilih.</p>
        </div>
        @endif
    </div>
</div>
@endsection
