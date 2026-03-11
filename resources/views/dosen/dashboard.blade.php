@extends('layouts.dosen')

@section('title', 'Dashboard Dosen Pembimbing')

@section('content')
<div class="space-y-8">
    <!-- Stat Overview -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-6">
            <div class="bg-blue-600 p-5 rounded-2xl shadow-lg shadow-blue-200">
                <i class="fas fa-chalkboard-teacher text-white text-3xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::guard('dosen')->user()->nama }}</h3>
                <p class="text-gray-500">Anda memiliki <span class="font-bold text-blue-600">{{ $mahasiswaBimbingan->count() }}</span> mahasiswa bimbingan aktif.</p>
            </div>
        </div>
    </div>

    <!-- Student List -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <h4 class="text-lg font-bold text-gray-800">Daftar Mahasiswa Bimbingan</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Mahasiswa</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Program & Penempatan</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Nilai Akhir</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($mahasiswaBimbingan as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
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
                                    @else
                                        {{ $item->mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                                    @endif
                                </p>
                            </div>
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
    </div>
</div>
@endsection
