@extends('layouts.adminmhs')

@section('title', 'Teman Se-Lokasi')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-6">
            <div class="bg-blue-600 p-5 rounded-2xl shadow-lg shadow-blue-200">
                <i class="fas fa-users text-white text-3xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Teman Se-Lokasi</h3>
                @if($namaLokasi)
                    <p class="text-gray-500">Penempatan: <span class="font-bold text-blue-600">{{ $namaLokasi }}</span></p>
                @else
                    <p class="text-gray-500">Anda belum memiliki penempatan lokasi.</p>
                @endif
            </div>
        </div>
        @if($temanSeLokasi->isNotEmpty())
        <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-sm font-black border border-blue-100">
            {{ $temanSeLokasi->count() }} orang
        </span>
        @endif
    </div>

    @if($namaLokasi)
        @if($temanSeLokasi->isNotEmpty())
        <!-- Student List -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50">
                <h4 class="text-lg font-bold text-gray-800">Daftar Teman {{ $kegiatan }} di {{ $namaLokasi }}</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">No</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Mahasiswa</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Program Studi</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Kampus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($temanSeLokasi as $i => $teman)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-8 py-5 text-sm text-gray-400 font-bold">{{ $i + 1 }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs group-hover:scale-110 transition-transform">
                                        {{ substr($teman->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $teman->nama }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $teman->nim }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-medium text-gray-700">{{ $teman->prodi_full }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm text-gray-600">{{ $teman->kampus }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-20 h-20 bg-blue-50 text-blue-300 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                <i class="fas fa-user-friends"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Teman Se-Lokasi</h3>
            <p class="text-gray-500 text-sm">Belum ada mahasiswa lain yang ditempatkan di <span class="font-bold">{{ $namaLokasi }}</span>.</p>
        </div>
        @endif
    @else
    <!-- No Placement -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
        <div class="w-20 h-20 bg-amber-50 text-amber-300 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Penempatan</h3>
        <p class="text-gray-500 text-sm">Anda belum ditempatkan di lokasi manapun. Mohon menunggu proses penempatan oleh admin.</p>
    </div>
    @endif
</div>
@endsection
