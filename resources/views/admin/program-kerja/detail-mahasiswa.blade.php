@extends('layouts.adminmhs')

@section('title', 'Detail Program Kerja - ' . $mahasiswa->nama)

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-900">{{ $mahasiswa->nama }}</h2>
            <p class="text-gray-500 mt-1">NIM: {{ $mahasiswa->nim }}</p>
        </div>
        <a href="{{ route('admin.program-kerja.dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Mahasiswa Info -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Informasi Mahasiswa</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">NIM</p>
                <p class="text-lg font-bold text-gray-900">{{ $mahasiswa->nim }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Program Studi</p>
                <p class="text-lg font-bold text-gray-900">{{ Str::limit($mahasiswa->prodi_full ?? '-', 20) }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Kegiatan</p>
                <p class="text-lg font-bold text-gray-900">{{ $mahasiswa->kegiatan ?? 'Belum Terdaftar' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Terdaftar Sejak</p>
                <p class="text-lg font-bold text-gray-900">{{ $mahasiswa->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Program Kerja Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Program Kerja ({{ $programs->count() }})</h3>

        @if ($programs->count() > 0)
            <div class="space-y-6">
                @foreach ($programs as $program)
                    <div class="border border-gray-200 rounded-2xl p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $program->judul }}</h4>
                                <p class="text-gray-600 text-sm mt-1">{{ $program->deskripsi }}</p>
                            </div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                @if($program->status === 'rencana') bg-blue-100 text-blue-800
                                @elseif($program->status === 'sedang_berjalan') bg-orange-100 text-orange-800
                                @elseif($program->status === 'selesai') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ str_replace('_', ' ', ucfirst($program->status)) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4 text-sm">
                            <div>
                                <p class="text-gray-600 text-xs font-semibold">LOKASI</p>
                                <p class="text-gray-900 font-medium">{{ $program->lokasi }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-semibold">TANGGAL MULAI</p>
                                <p class="text-gray-900 font-medium">{{ $program->tanggal_mulai->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-semibold">TANGGAL SELESAI</p>
                                <p class="text-gray-900 font-medium">{{ $program->tanggal_selesai->format('d F Y') }}</p>
                            </div>
                        </div>

                        <!-- Luaran for this program -->
                        @php
                            $programLuarans = $program->luarans ?? $luarans->where('individu_program_kerja_id', $program->id);
                            $monev = $program->monev ?? $program->dosenMonev;
                        @endphp

                        <!-- Dosen Monev Section -->
                        @if ($monev && $monev->dosen)
                            <div class="mt-4 pt-4 border-t border-gray-100 bg-indigo-50/40 p-4 rounded-xl border border-indigo-100">
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-search-location text-indigo-600 text-sm"></i>
                                        <span class="text-xs font-bold text-gray-900">Dosen Monev: {{ $monev->dosen->nama }} (NIDN: {{ $monev->dosen->nidn }})</span>
                                    </div>
                                    @if(!is_null($monev->nilai))
                                        <span class="px-2.5 py-0.5 bg-indigo-600 text-white font-black text-xs rounded-lg">
                                            Nilai Monev: {{ $monev->nilai }}
                                        </span>
                                    @endif
                                </div>
                                @if($monev->tanggal_monev)
                                    <p class="text-[11px] text-gray-500 mb-2">Tanggal Monev: <strong>{{ \Carbon\Carbon::parse($monev->tanggal_monev)->format('d M Y') }}</strong></p>
                                @endif
                                @if($monev->catatan)
                                    <div class="p-3 bg-white rounded-lg border border-indigo-100 text-xs text-gray-700 leading-relaxed mb-2">
                                        <strong class="text-indigo-900 block mb-0.5">Catatan Monev:</strong>
                                        {{ $monev->catatan }}
                                    </div>
                                @endif
                                @if($monev->link_monev)
                                    <div class="mb-2">
                                        <a href="{{ $monev->link_monev }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                            <i class="fab fa-google-drive"></i>
                                            <span>Buka Dokumentasi di Google Drive</span>
                                            <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                                        </a>
                                    </div>
                                @endif
                                @if(!empty($monev->foto_monev) && count($monev->foto_monev) > 0)
                                    <div class="flex items-center gap-2 overflow-x-auto py-1">
                                        @foreach($monev->foto_monev as $f)
                                            <a href="{{ asset('storage/' . ltrim($f, '/')) }}" target="_blank" class="block w-16 h-16 rounded-lg overflow-hidden border border-gray-200 bg-black flex-shrink-0">
                                                <img src="{{ asset('storage/' . ltrim($f, '/')) }}" class="w-full h-full object-cover hover:opacity-80 transition">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($programLuarans->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-3">Luaran ({{ $programLuarans->count() }})</p>
                                <div class="space-y-2">
                                    @foreach ($programLuarans as $luaran)
                                        <div class="bg-gray-50 p-3 rounded-lg flex items-start justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $luaran->judul }}</p>
                                                <p class="text-xs text-gray-600">{{ $luaran->tipe }} • {{ $luaran->persentase_selesai }}% selesai</p>
                                            </div>
                                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                                @if($luaran->status === 'belum_dikerjakan') bg-red-100 text-red-800
                                                @elseif($luaran->status === 'sedang_dikerjakan') bg-orange-100 text-orange-800
                                                @else bg-green-100 text-green-800
                                                @endif
                                            ">
                                                {{ str_replace('_', ' ', ucfirst($luaran->status)) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-600">Mahasiswa belum membuat program kerja</p>
            </div>
        @endif
    </div>

    <!-- All Luaran Section -->
    @if ($luarans->count() > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Semua Luaran ({{ $luarans->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Program Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Tipe</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Progress</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($luarans as $luaran)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $luaran->judul }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $luaran->programKerja->judul ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($luaran->tipe) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $luaran->persentase_selesai }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-600">{{ $luaran->persentase_selesai }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        @if($luaran->status === 'belum_dikerjakan') bg-red-100 text-red-800
                                        @elseif($luaran->status === 'sedang_dikerjakan') bg-orange-100 text-orange-800
                                        @else bg-green-100 text-green-800
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', ucfirst($luaran->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
