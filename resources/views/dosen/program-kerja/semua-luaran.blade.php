@extends('layouts.main')

@section('title', 'Semua Luaran / Deliverables')

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div class="space-y-8" x-data="{ activeTab: '{{ request('tab', 'individu') }}' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-xs font-semibold uppercase tracking-wider mb-2">
                <i class="fas fa-boxes"></i> Monitoring Deliverables
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Semua Luaran / Deliverables</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau seluruh target luaran dan berkas deliverable dari program kerja mahasiswa.</p>
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
            <span>Luaran Individu</span>
            <span class="px-2.5 py-0.5 text-xs font-black rounded-full" :class="activeTab === 'individu' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'">
                {{ $individuLuarans->total() }}
            </span>
        </button>
        <button @click="activeTab = 'kelompok'" 
            :class="activeTab === 'kelompok' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
            class="pb-3 px-4 border-b-2 text-sm transition-all flex items-center gap-2">
            <i class="fas fa-users text-sm"></i>
            <span>Luaran Kelompok</span>
            <span class="px-2.5 py-0.5 text-xs font-black rounded-full" :class="activeTab === 'kelompok' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600'">
                {{ $kelompokLuarans->total() }}
            </span>
        </button>
    </div>

    <!-- TAB 1: LUARAN INDIVIDU -->
    <div x-show="activeTab === 'individu'" class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/75 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Judul Luaran & Berkas</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Program Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($individuLuarans as $luaran)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                    <div class="font-bold">{{ $luaran->judul }}</div>
                                    @if ($luaran->file_path)
                                        <a href="{{ str_starts_with($luaran->file_path, 'http') ? $luaran->file_path : asset('storage/' . $luaran->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium mt-1">
                                            <i class="fas fa-paperclip text-[10px]"></i> Lihat Berkas
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($luaran->programKerja?->mahasiswa)
                                        <a href="{{ route('dosen.program-kerja.detail', $luaran->programKerja->mahasiswa) }}" class="text-blue-600 hover:text-blue-700 font-bold">
                                            {{ $luaran->programKerja->mahasiswa->nama }}
                                        </a>
                                        <div class="text-xs text-gray-500 font-mono mt-0.5">NIM: {{ $luaran->programKerja->nim }}</div>
                                    @else
                                        <span class="text-gray-400 font-mono text-xs">{{ $luaran->programKerja?->nim ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium max-w-xs">
                                    <span class="truncate block">{{ $luaran->programKerja?->judul ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold uppercase">
                                        {{ $luaran->tipe }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $luaran->persentase_selesai }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 w-8">{{ $luaran->persentase_selesai }}%</span>
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
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4 text-2xl border border-blue-100">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                        <p class="text-base font-bold text-gray-900">Belum Ada Luaran Individu</p>
                                        <p class="text-xs text-gray-400 mt-1">Mahasiswa bimbingan Anda belum mengunggah berkas/target luaran individu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Tab 1 -->
        @if ($individuLuarans->hasPages())
            <div class="mt-6">
                {{ $individuLuarans->appends(['tab' => 'individu'])->links() }}
            </div>
        @endif
    </div>

    <!-- TAB 2: LUARAN KELOMPOK -->
    <div x-show="activeTab === 'kelompok'" class="space-y-6" style="display: none;">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/75 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Judul Luaran & Berkas</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Ketua / Kelompok</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Program Kerja Kelompok</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($kelompokLuarans as $luaran)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                    <div class="font-bold">{{ $luaran->judul }}</div>
                                    @if ($luaran->file_path)
                                        <a href="{{ str_starts_with($luaran->file_path, 'http') ? $luaran->file_path : asset('storage/' . $luaran->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-purple-600 hover:text-purple-800 font-medium mt-1">
                                            <i class="fas fa-paperclip text-[10px]"></i> Lihat Berkas
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-black rounded uppercase">Kelompok</span>
                                    </div>
                                    @if ($luaran->programKerja?->mahasiswaKetua)
                                        <a href="{{ route('dosen.program-kerja.detail', $luaran->programKerja->mahasiswaKetua) }}" class="text-purple-600 hover:text-purple-700 font-bold">
                                            {{ $luaran->programKerja->mahasiswaKetua->nama }}
                                        </a>
                                        <div class="text-xs text-gray-500 font-mono mt-0.5">Ketua NIM: {{ $luaran->programKerja->nim_ketua }}</div>
                                    @else
                                        <span class="text-gray-400 font-mono text-xs">{{ $luaran->programKerja?->nim_ketua ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium max-w-xs">
                                    <span class="truncate block">{{ $luaran->programKerja?->judul ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold uppercase">
                                        {{ $luaran->tipe }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $luaran->persentase_selesai }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 w-8">{{ $luaran->persentase_selesai }}%</span>
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
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mb-4 text-2xl border border-purple-100">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                        <p class="text-base font-bold text-gray-900">Belum Ada Luaran Kelompok</p>
                                        <p class="text-xs text-gray-400 mt-1">Belum ada target luaran kelompok yang diunggah untuk kelompok lokasi ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Tab 2 -->
        @if ($kelompokLuarans->hasPages())
            <div class="mt-6">
                {{ $kelompokLuarans->appends(['tab' => 'kelompok'])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

