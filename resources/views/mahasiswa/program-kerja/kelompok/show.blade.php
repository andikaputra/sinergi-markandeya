@extends('layouts.adminmhs')

@section('title', $kelompokProgramKerja->judul)

@section('content')
@php
    $currentMhsNim = auth('mahasiswa')->user()->nim;
    $isKetua = $currentMhsNim === $kelompokProgramKerja->nim_ketua;
    $isMember = in_array($currentMhsNim, $anggota->pluck('nim')->toArray()) || $isKetua;
@endphp

<div class="space-y-6" x-data="{ showLuaranForm: false }">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <a href="{{ route('program-kerja.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1 mb-1">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Program Kerja
            </a>
            <div class="flex items-center gap-2 mt-1">
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ ucfirst($kelompokProgramKerja->kategori) }} • KELOMPOK
                </span>
                @if ($kelompokProgramKerja->status === 'rencana')
                    <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">Rencana</span>
                @elseif ($kelompokProgramKerja->status === 'sedang_berjalan')
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold rounded-full">Sedang Berjalan</span>
                @elseif ($kelompokProgramKerja->status === 'selesai')
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">Selesai</span>
                @else
                    <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">{{ ucfirst($kelompokProgramKerja->status) }}</span>
                @endif
            </div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight mt-2">{{ $kelompokProgramKerja->judul }}</h2>
        </div>
        @if ($isKetua)
        <div class="flex items-center gap-2">
            <a href="{{ route('program-kerja.edit-kelompok', $kelompokProgramKerja) }}" class="px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-bold rounded-2xl transition-all border border-amber-200">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('program-kerja.destroy-kelompok', $kelompokProgramKerja) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus program kerja kelompok ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-sm font-bold rounded-2xl transition-all border border-rose-200">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </form>
        </div>
        @endif
    </div>

    @if ($message = Session::get('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="text-sm font-medium">{{ $message }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Column: Deskripsi, Anggota, & Luaran -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Deskripsi Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-align-left text-indigo-600 text-sm"></i>
                    Deskripsi Program Kerja Kelompok
                </h3>
                <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                    {{ $kelompokProgramKerja->deskripsi }}
                </div>
            </div>

            <!-- Anggota Kelompok Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-indigo-600 text-sm"></i>
                    Anggota Kelompok di Lokasi Penempatan ({{ $anggota->count() }})
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($anggota as $member)
                        <div class="p-3.5 bg-gray-50/70 rounded-2xl border border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $member->nim === $kelompokProgramKerja->nim_ketua ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} flex items-center justify-center font-bold text-xs">
                                    {{ substr($member->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-800">{{ $member->nama }}</div>
                                    <div class="text-[11px] text-gray-400 font-medium">{{ $member->nim }}</div>
                                </div>
                            </div>
                            @if ($member->nim === $kelompokProgramKerja->nim_ketua)
                                <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-full">Ketua</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded-full">Anggota</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Luaran / Deliverables Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-tasks text-indigo-600 text-sm"></i>
                            Luaran & Target Deliverables
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Produk atau hasil kerja kelompok yang dapat dikolaborasikan bersama.</p>
                    </div>
                    @if ($isMember)
                    <button @click="showLuaranForm = !showLuaranForm" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-2xl transition shadow-md shadow-indigo-200">
                        <i class="fas fa-plus mr-1"></i> Tambah Luaran
                    </button>
                    @endif
                </div>

                <!-- Add Form Toggle -->
                @if ($isMember)
                <div x-show="showLuaranForm" x-cloak class="mb-6 p-6 bg-indigo-50/40 rounded-3xl border border-indigo-100">
                    <h4 class="text-sm font-bold text-indigo-900 mb-4">Form Tambah Luaran Kelompok</h4>
                    <form action="{{ route('luaran.store-kelompok', $kelompokProgramKerja) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Judul Luaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white" placeholder="Contoh: Laporan Akhir Program Kerja Kelompok" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Luaran <span class="text-rose-500">*</span></label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white" placeholder="Rincian output luaran yang dicapai bersama kelompok..." required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Luaran <span class="text-rose-500">*</span></label>
                                <input type="text" name="tipe" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white" placeholder="Contoh: Dokumen, Video, Modul, Publikasi" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Target Selesai <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_selesai" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Link File / URL Hasil (Opsional)</label>
                            <input type="url" name="file_path" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white" placeholder="https://drive.google.com/...">
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="showLuaranForm = false" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-md shadow-indigo-200">
                                Simpan Luaran
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Luaran Items -->
                @if ($luarans->isEmpty())
                    <div class="p-8 text-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                        <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-600">Belum ada deliverable/luaran untuk program kerja kelompok ini.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($luarans as $luaran)
                            <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-700 text-[11px] font-bold rounded-lg uppercase">{{ $luaran->tipe }}</span>
                                            <h4 class="font-bold text-gray-800 text-base">{{ $luaran->judul }}</h4>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">Target: {{ $luaran->tanggal_selesai ? $luaran->tanggal_selesai->format('d M Y') : '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if ($luaran->file_path)
                                            <a href="{{ $luaran->file_path }}" target="_blank" class="px-3 py-1 bg-white border border-gray-200 text-indigo-600 hover:text-indigo-700 text-xs font-bold rounded-xl inline-flex items-center gap-1 shadow-sm">
                                                <i class="fas fa-external-link-alt text-[10px]"></i> Link Hasil
                                            </a>
                                        @endif
                                        @if ($isMember)
                                        <form action="{{ route('luaran.destroy', ['type' => 'kelompok', 'luaranId' => $luaran->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus luaran ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg text-xs" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-xs text-gray-600 leading-relaxed">{{ $luaran->deskripsi }}</p>

                                <!-- Status Updater / Display -->
                                @if ($isMember)
                                <form action="{{ route('luaran.update-status', ['type' => 'kelompok', 'luaranId' => $luaran->id]) }}" method="POST" class="pt-3 border-t border-gray-200/60 flex flex-wrap items-center gap-3">
                                    @csrf @method('PUT')
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs font-bold text-gray-600">Status:</label>
                                        <select name="status" class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                                            <option value="belum_dikerjakan" @selected($luaran->status === 'belum_dikerjakan')>Belum Dikerjakan</option>
                                            <option value="sedang_dikerjakan" @selected($luaran->status === 'sedang_dikerjakan')>Sedang Dikerjakan</option>
                                            <option value="selesai" @selected($luaran->status === 'selesai')>Selesai</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs font-bold text-gray-600">Progres:</label>
                                        <div class="flex items-center gap-1">
                                            <input type="number" name="persentase_selesai" min="0" max="100" value="{{ $luaran->persentase_selesai }}" class="w-16 px-2 py-1 bg-white border border-gray-200 rounded-xl text-xs text-center font-bold">
                                            <span class="text-xs font-bold text-gray-500">%</span>
                                        </div>
                                    </div>
                                    <button type="submit" class="px-3 py-1.5 bg-gray-800 hover:bg-black text-white text-xs font-bold rounded-xl transition shadow-sm ml-auto">
                                        Simpan Progres
                                    </button>
                                </form>
                                @else
                                <div class="pt-3 border-t border-gray-200/60 flex items-center gap-3 text-xs">
                                    <span class="font-bold text-gray-500">Status:</span>
                                    @if ($luaran->status === 'belum_dikerjakan')
                                        <span class="text-gray-600 font-bold">Belum Dikerjakan</span>
                                    @elseif ($luaran->status === 'sedang_dikerjakan')
                                        <span class="text-blue-600 font-bold">Sedang Dikerjakan ({{ $luaran->persentase_selesai }}%)</span>
                                    @else
                                        <span class="text-emerald-600 font-bold">Selesai (100%)</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Program Meta Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Informasi Program</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start justify-between gap-2 pb-3 border-b border-gray-100">
                        <span class="text-gray-500 text-xs font-medium">Ketua Pengusul</span>
                        <span class="font-bold text-gray-800 text-xs text-right">{{ $kelompokProgramKerja->mahasiswaKetua->nama ?? '-' }} ({{ $kelompokProgramKerja->nim_ketua }})</span>
                    </div>
                    <div class="flex items-start justify-between gap-2 pb-3 border-b border-gray-100">
                        <span class="text-gray-500 text-xs font-medium">Lokasi</span>
                        <span class="font-bold text-gray-800 text-xs text-right">{{ $kelompokProgramKerja->lokasi }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2 pb-3 border-b border-gray-100">
                        <span class="text-gray-500 text-xs font-medium">Tanggal Mulai</span>
                        <span class="font-bold text-gray-800 text-xs">{{ $kelompokProgramKerja->tanggal_mulai ? $kelompokProgramKerja->tanggal_mulai->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2 pb-3 border-b border-gray-100">
                        <span class="text-gray-500 text-xs font-medium">Tanggal Selesai</span>
                        <span class="font-bold text-gray-800 text-xs">{{ $kelompokProgramKerja->tanggal_selesai ? $kelompokProgramKerja->tanggal_selesai->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-500 text-xs font-medium">Target Luaran</span>
                        <span class="font-bold text-gray-800 text-xs">{{ $statistikLuaran['selesai'] }} / {{ $statistikLuaran['total'] }} Selesai</span>
                    </div>
                </div>
            </div>

            <!-- Dosen Monev Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Dosen Monitoring & Evaluasi</h3>
                @if ($dosenMonev && $dosenMonev->dosen)
                    <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                                {{ substr($dosenMonev->dosen->nama, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 text-sm">{{ $dosenMonev->dosen->nama }}</div>
                                <div class="text-[11px] text-gray-500">NIDN: {{ $dosenMonev->dosen->nidn }}</div>
                            </div>
                        </div>
                        @if ($dosenMonev->nilai)
                            <div class="mt-3 pt-3 border-t border-indigo-200/60 flex items-center justify-between">
                                <span class="text-xs text-indigo-900 font-medium">Nilai Monev:</span>
                                <span class="text-lg font-black text-indigo-700">{{ $dosenMonev->nilai }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <p class="text-xs text-gray-500">Belum ada dosen monev yang ditugaskan untuk program kelompok ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
