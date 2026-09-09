@extends('layouts.main')

@section('title', 'Semua Program Kerja')

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div class="space-y-8" x-data="{ activeTab: '{{ request('tab', 'individu') }}' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold uppercase tracking-wider mb-2">
                <i class="fas fa-tasks"></i> Monitoring Program Kerja
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Semua Program Kerja</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar seluruh program kerja individu & kelompok mahasiswa bimbingan Anda.</p>
        </div>
        <a href="{{ route('dosen.program-kerja.dashboard') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-2xl border border-gray-200 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex border-b border-gray-200 gap-4">
        <button @click="activeTab = 'individu'" 
            :class="activeTab === 'individu' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
            class="pb-3 px-4 border-b-2 text-sm transition-all flex items-center gap-2">
            <i class="fas fa-user text-sm"></i>
            <span>Program Kerja Individu</span>
            <span class="px-2.5 py-0.5 text-xs font-black rounded-full" :class="activeTab === 'individu' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'">
                {{ $individuPrograms->total() }}
            </span>
        </button>
        <button @click="activeTab = 'kelompok'" 
            :class="activeTab === 'kelompok' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
            class="pb-3 px-4 border-b-2 text-sm transition-all flex items-center gap-2">
            <i class="fas fa-users text-sm"></i>
            <span>Program Kerja Kelompok</span>
            <span class="px-2.5 py-0.5 text-xs font-black rounded-full" :class="activeTab === 'kelompok' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600'">
                {{ $kelompokPrograms->total() }}
            </span>
        </button>
    </div>

    <!-- TAB 1: PROGRAM INDIVIDU -->
    <div x-show="activeTab === 'individu'" class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/75 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Judul & Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jadwal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Luaran</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($individuPrograms as $program)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 text-sm">
                                    @if($program->mahasiswa)
                                        <a href="{{ route('dosen.program-kerja.detail', $program->mahasiswa) }}" class="text-blue-600 hover:text-blue-800 font-bold">
                                            {{ $program->mahasiswa->nama }}
                                        </a>
                                    @else
                                        <span class="font-bold text-gray-900">-</span>
                                    @endif
                                    <div class="text-xs text-gray-500 font-mono mt-0.5">NIM: {{ $program->nim }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs">
                                    <div class="font-bold text-gray-900">{{ $program->judul }}</div>
                                    @if($program->lokasi)
                                        <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-rose-500 text-[10px]"></i>
                                            <span class="truncate">{{ $program->lokasi }}</span>
                                        </div>
                                    @endif
                                    @if($program->catatan_dosen)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md border border-blue-100" title="{{ $program->catatan_dosen }}">
                                                <i class="fas fa-comment-dots text-[9px]"></i> Ada Catatan Pembimbing
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}</div>
                                    <div class="text-gray-400">s/d {{ $program->tanggal_selesai ? $program->tanggal_selesai->format('d M Y') : '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold border border-blue-100">
                                        <i class="fas fa-box-open text-[10px]"></i>
                                        {{ $program->luarans ? $program->luarans->count() : 0 }} Luaran
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        @if($program->status === 'rencana') bg-blue-100 text-blue-800
                                        @elseif($program->status === 'sedang_berjalan') bg-orange-100 text-orange-800
                                        @elseif($program->status === 'selesai') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', ucfirst($program->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-center">
                                    @if($program->mahasiswa)
                                        <a href="{{ route('dosen.program-kerja.detail', $program->mahasiswa) }}" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shadow-sm">
                                            <i class="fas fa-eye text-[10px]"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4 text-2xl border border-blue-100">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <p class="text-base font-bold text-gray-900">Belum Ada Program Kerja Individu</p>
                                        <p class="text-xs text-gray-400 mt-1">Mahasiswa bimbingan Anda belum membuat program kerja individu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Tab 1 -->
        @if ($individuPrograms->hasPages())
            <div class="mt-6">
                {{ $individuPrograms->appends(['tab' => 'individu'])->links() }}
            </div>
        @endif
    </div>

    <!-- TAB 2: PROGRAM KELOMPOK -->
    <div x-show="activeTab === 'kelompok'" class="space-y-6" style="display: none;">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/75 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Ketua & Kelompok</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Judul & Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jadwal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Luaran</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($kelompokPrograms as $program)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-black rounded uppercase">Kelompok</span>
                                    </div>
                                    @if($program->mahasiswaKetua)
                                        <a href="{{ route('dosen.program-kerja.detail', $program->mahasiswaKetua) }}" class="text-purple-600 hover:text-purple-800 font-bold">
                                            {{ $program->mahasiswaKetua->nama }}
                                        </a>
                                    @else
                                        <span class="font-bold text-gray-900">-</span>
                                    @endif
                                    <div class="text-xs text-gray-500 font-mono mt-0.5">Ketua NIM: {{ $program->nim_ketua }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs">
                                    <div class="font-bold text-gray-900">{{ $program->judul }}</div>
                                    @if($program->lokasi)
                                        <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-rose-500 text-[10px]"></i>
                                            <span class="truncate">{{ $program->lokasi }}</span>
                                        </div>
                                    @endif
                                    @if($program->catatan_dosen)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded-md border border-purple-100" title="{{ $program->catatan_dosen }}">
                                                <i class="fas fa-comment-dots text-[9px]"></i> Ada Catatan Pembimbing
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}</div>
                                    <div class="text-gray-400">s/d {{ $program->tanggal_selesai ? $program->tanggal_selesai->format('d M Y') : '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 font-bold border border-purple-100">
                                        <i class="fas fa-box-open text-[10px]"></i>
                                        {{ $program->luarans ? $program->luarans->count() : 0 }} Luaran
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        @if($program->status === 'rencana') bg-blue-100 text-blue-800
                                        @elseif($program->status === 'sedang_berjalan') bg-orange-100 text-orange-800
                                        @elseif($program->status === 'selesai') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', ucfirst($program->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-center">
                                    @if($program->mahasiswaKetua)
                                        <a href="{{ route('dosen.program-kerja.detail', $program->mahasiswaKetua) }}" class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shadow-sm">
                                            <i class="fas fa-eye text-[10px]"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mb-4 text-2xl border border-purple-100">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <p class="text-base font-bold text-gray-900">Belum Ada Program Kerja Kelompok</p>
                                        <p class="text-xs text-gray-400 mt-1">Belum ada program kerja kelompok yang diinput untuk lokasi penempatan mahasiswa bimbingan Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Tab 2 -->
        @if ($kelompokPrograms->hasPages())
            <div class="mt-6">
                {{ $kelompokPrograms->appends(['tab' => 'kelompok'])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

