@extends('layouts.adminmhs')

@section('title', $individuProgramKerja->judul)

@section('content')
<div class="space-y-6" x-data="{ showLuaranForm: false }">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <a href="{{ route('program-kerja.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1 mb-1">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Program Kerja
            </a>
            <div class="flex items-center gap-2 mt-1">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ ucfirst($individuProgramKerja->kategori) }} • INDIVIDU
                </span>
                @if ($individuProgramKerja->status === 'rencana')
                    <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">Rencana</span>
                @elseif ($individuProgramKerja->status === 'sedang_berjalan')
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold rounded-full">Sedang Berjalan</span>
                @elseif ($individuProgramKerja->status === 'selesai')
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">Selesai</span>
                @else
                    <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">{{ ucfirst($individuProgramKerja->status) }}</span>
                @endif
            </div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight mt-2">{{ $individuProgramKerja->judul }}</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('program-kerja.edit-individu', $individuProgramKerja) }}" class="px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-bold rounded-2xl transition-all border border-amber-200">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('program-kerja.destroy-individu', $individuProgramKerja) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus program kerja ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-sm font-bold rounded-2xl transition-all border border-rose-200">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="text-sm font-medium">{{ $message }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Column: Deskripsi & Luaran -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Deskripsi Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-align-left text-blue-600 text-sm"></i>
                    Deskripsi Program Kerja
                </h3>
                <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                    {{ $individuProgramKerja->deskripsi }}
                </div>
            </div>

            <!-- Luaran / Deliverables Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-tasks text-blue-600 text-sm"></i>
                            Luaran & Target Deliverables
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Produk, laporan, atau output fisik/digital yang dihasilkan dari program kerja.</p>
                    </div>
                    <button @click="showLuaranForm = !showLuaranForm" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-2xl transition shadow-md shadow-blue-200">
                        <i class="fas fa-plus mr-1"></i> Tambah Luaran
                    </button>
                </div>

                <!-- Add Form Toggle -->
                <div x-show="showLuaranForm" x-cloak class="mb-6 p-6 bg-blue-50/40 rounded-3xl border border-blue-100">
                    <h4 class="text-sm font-bold text-blue-900 mb-4">Form Tambah Luaran</h4>
                    <form action="{{ route('luaran.store-individu', $individuProgramKerja) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Judul Luaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" placeholder="Contoh: Laporan Modul Panduan Pengguna" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Luaran <span class="text-rose-500">*</span></label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" placeholder="Rincian output luaran yang dicapai..." required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Luaran <span class="text-rose-500">*</span></label>
                                <input type="text" name="tipe" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" placeholder="Contoh: Dokumen, Video, Aplikasi, Publikasi" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Target Selesai <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_selesai" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Link File / URL Hasil (Opsional)</label>
                            <input type="url" name="file_path" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" placeholder="https://drive.google.com/...">
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="showLuaranForm = false" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs shadow-md shadow-blue-200">
                                Simpan Luaran
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Luaran Items -->
                @if ($luarans->isEmpty())
                    <div class="p-8 text-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                        <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-600">Belum ada deliverable/luaran untuk program kerja ini.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($luarans as $luaran)
                            <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 text-[11px] font-bold rounded-lg uppercase">{{ $luaran->tipe }}</span>
                                            <h4 class="font-bold text-gray-800 text-base">{{ $luaran->judul }}</h4>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">Target: {{ $luaran->tanggal_selesai ? $luaran->tanggal_selesai->format('d M Y') : '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if ($luaran->file_path)
                                            <a href="{{ $luaran->file_path }}" target="_blank" class="px-3 py-1 bg-white border border-gray-200 text-blue-600 hover:text-blue-700 text-xs font-bold rounded-xl inline-flex items-center gap-1 shadow-sm">
                                                <i class="fas fa-external-link-alt text-[10px]"></i> Link Hasil
                                            </a>
                                        @endif
                                        <form action="{{ route('luaran.destroy', ['type' => 'individu', 'luaranId' => $luaran->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus luaran ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg text-xs" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <p class="text-xs text-gray-600 leading-relaxed">{{ $luaran->deskripsi }}</p>

                                <!-- Status Updater -->
                                <form action="{{ route('luaran.update-status', ['type' => 'individu', 'luaranId' => $luaran->id]) }}" method="POST" class="pt-3 border-t border-gray-200/60 flex flex-wrap items-center gap-3">
                                    @csrf @method('PUT')
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs font-bold text-gray-600">Status:</label>
                                        <select name="status" class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-500">
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
                        <span class="text-gray-500 text-xs font-medium">Lokasi</span>
                        <span class="font-bold text-gray-800 text-xs text-right">{{ $individuProgramKerja->lokasi }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2 pb-3 border-b border-gray-100">
                        <span class="text-gray-500 text-xs font-medium">Tanggal Mulai</span>
                        <span class="font-bold text-gray-800 text-xs">{{ $individuProgramKerja->tanggal_mulai ? $individuProgramKerja->tanggal_mulai->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2 pb-3 border-b border-gray-100">
                        <span class="text-gray-500 text-xs font-medium">Tanggal Selesai</span>
                        <span class="font-bold text-gray-800 text-xs">{{ $individuProgramKerja->tanggal_selesai ? $individuProgramKerja->tanggal_selesai->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-500 text-xs font-medium">Target Luaran</span>
                        <span class="font-bold text-gray-800 text-xs">{{ $statistikLuaran['selesai'] }} / {{ $statistikLuaran['total'] }} Selesai</span>
                    </div>
                </div>
            </div>

            <!-- Dosen Pembimbing Notes Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                        <span>Catatan Dosen Pembimbing</span>
                    </h3>
                    @if ($individuProgramKerja->catatan_dosen)
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold rounded-full flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Ada Catatan
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full flex items-center gap-1">
                            <i class="fas fa-clock"></i> Belum Direview
                        </span>
                    @endif
                </div>

                @php
                    $dosenReviewer = $individuProgramKerja->dosenCatatan ?? ($dosenPembimbing ?? null);
                @endphp

                <div class="space-y-4">
                    @if ($dosenReviewer)
                        <div class="p-4 bg-blue-50/60 rounded-2xl border border-blue-100 flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-base shadow-sm flex-shrink-0">
                                {{ substr($dosenReviewer->nama, 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-gray-900 text-sm truncate">{{ $dosenReviewer->nama }}</div>
                                <div class="text-[11px] text-gray-500 font-mono">Dosen Pembimbing • NIDN: {{ $dosenReviewer->nidn }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($individuProgramKerja->catatan_dosen)
                        <div class="p-4 bg-gradient-to-br from-blue-50 via-indigo-50/50 to-white border border-blue-200 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between text-xs text-blue-900 font-bold">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-quote-left text-blue-500 text-xs"></i>
                                    Arahan / Catatan Bimbingan:
                                </span>
                                @if ($individuProgramKerja->catatan_dosen_at)
                                    <span class="text-[10px] text-gray-500 font-normal">
                                        {{ $individuProgramKerja->catatan_dosen_at->format('d M Y, H:i') }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-800 leading-relaxed whitespace-pre-wrap font-medium pl-3 border-l-2 border-blue-500">
                                {{ $individuProgramKerja->catatan_dosen }}
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                            <i class="fas fa-comment-slash text-gray-300 text-xl mb-1.5 block"></i>
                            <p class="text-xs text-gray-500 font-medium">Belum ada catatan atau arahan dari Dosen Pembimbing untuk program kerja ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dosen Monev Card (3 Tahap) -->
            <div x-data="{ activeMonevTab: 1, monevImage: null }" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-search-location text-indigo-600"></i>
                        <span>Evaluasi & Monev Lapangan</span>
                    </h3>
                    @if ($dosenMonev)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $dosenMonev->monev_selesai_count == 3 ? 'bg-emerald-100 text-emerald-800' : ($dosenMonev->monev_selesai_count > 0 ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $dosenMonev->monev_selesai_count }}/3 Monev Selesai
                        </span>
                    @endif
                </div>

                @if ($dosenMonev && $dosenMonev->dosen)
                    <!-- Profil Dosen Monev -->
                    <div class="p-4 bg-indigo-50/60 rounded-2xl border border-indigo-100 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black text-base shadow-sm flex-shrink-0">
                                {{ substr($dosenMonev->dosen->nama, 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-gray-900 text-sm truncate">{{ $dosenMonev->dosen->nama }}</div>
                                <div class="text-[11px] text-gray-500 font-mono">Dosen Pemonev • NIDN: {{ $dosenMonev->dosen->nidn }}</div>
                            </div>
                        </div>
                        @if (!is_null($dosenMonev->rata_rata_nilai))
                            <div class="text-right flex-shrink-0">
                                <span class="text-[10px] text-indigo-900 font-medium block">Rata-rata Nilai:</span>
                                <span class="px-2.5 py-0.5 bg-indigo-600 text-white font-black text-xs rounded-lg shadow-sm">
                                    {{ $dosenMonev->rata_rata_nilai }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- 3 Stage Tabs for Mahasiswa -->
                    @php
                        $mTahap1 = $dosenMonev->getTahap(1);
                        $mTahap2 = $dosenMonev->getTahap(2);
                        $mTahap3 = $dosenMonev->getTahap(3);
                        $mTahapList = [
                            1 => ['title' => 'Monev 1', 'sub' => 'Tahap Awal', 'data' => $mTahap1],
                            2 => ['title' => 'Monev 2', 'sub' => 'Tahap Progres', 'data' => $mTahap2],
                            3 => ['title' => 'Monev 3', 'sub' => 'Tahap Akhir', 'data' => $mTahap3],
                        ];
                    @endphp

                    <div class="flex gap-1.5 p-1 bg-gray-100 rounded-2xl">
                        @foreach ($mTahapList as $ke => $mInfo)
                            @php $isDone = $mInfo['data']->exists && $mInfo['data']->is_filled; @endphp
                            <button type="button" 
                                @click="activeMonevTab = {{ $ke }}"
                                :class="activeMonevTab === {{ $ke }} ? 'bg-white text-indigo-700 shadow-sm font-black' : 'text-gray-600 hover:text-gray-900 font-semibold'"
                                class="flex-1 py-2 px-2 rounded-xl text-xs text-center transition flex items-center justify-center gap-1.5">
                                <i class="fas {{ $isDone ? 'fa-check-circle text-emerald-500' : 'fa-clock text-gray-400' }} text-[10px]"></i>
                                <span>{{ $mInfo['title'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Tahap Contents -->
                    @foreach ($mTahapList as $ke => $mInfo)
                        @php 
                            $t = $mInfo['data']; 
                            $isDone = $t->exists && $t->is_filled;
                        @endphp
                        <div x-show="activeMonevTab === {{ $ke }}" class="space-y-4 pt-1">
                            <div class="flex items-center justify-between text-xs pb-2 border-b border-gray-100">
                                <span class="font-bold text-gray-800">{{ $mInfo['title'] }} - {{ $mInfo['sub'] }}</span>
                                @if ($isDone)
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i> Sudah Dievaluasi
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-full flex items-center gap-1">
                                        <i class="fas fa-clock"></i> Belum Dilaksanakan
                                    </span>
                                @endif
                            </div>

                            @if ($isDone)
                                <!-- Execution date & Grade -->
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        <span class="text-gray-400 block text-[11px]">Tgl Pelaksanaan</span>
                                        <span class="font-bold text-gray-900">{{ $t->tanggal_monev ? \Carbon\Carbon::parse($t->tanggal_monev)->format('d M Y') : '-' }}</span>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        <span class="text-gray-400 block text-[11px]">Nilai {{ $mInfo['title'] }}</span>
                                        <span class="font-black text-indigo-700">{{ !is_null($t->nilai) ? $t->nilai . ' / 100' : 'Belum dinilai' }}</span>
                                    </div>
                                </div>

                                <!-- Notes -->
                                @if ($t->catatan)
                                    <div class="p-4 bg-amber-50/80 border border-amber-200/80 rounded-2xl space-y-1.5">
                                        <p class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                                            <i class="fas fa-comment-dots text-amber-600"></i>
                                            <span>Catatan & Masukan Dosen Monev ({{ $mInfo['title'] }}):</span>
                                        </p>
                                        <div class="text-xs text-amber-950 leading-relaxed whitespace-pre-wrap font-normal">
                                            {{ $t->catatan }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Google Drive Link -->
                                @if ($t->link_monev)
                                    <div class="p-4 bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-emerald-500/10 border border-emerald-200 rounded-2xl space-y-2.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs shadow-sm">
                                                <i class="fab fa-google-drive"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-bold text-emerald-950">Dokumentasi Foto {{ $mInfo['title'] }}</h4>
                                                <p class="text-[10px] text-emerald-700">Tersedia di Google Drive</p>
                                            </div>
                                        </div>
                                        <a href="{{ $t->link_monev }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            <i class="fab fa-google-drive"></i>
                                            <span>Buka Foto di Google Drive</span>
                                            <i class="fas fa-external-link-alt text-[10px] ml-0.5"></i>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="p-5 bg-gray-50 rounded-2xl border border-dashed border-gray-200 text-center space-y-1">
                                    <i class="fas fa-hourglass-half text-gray-300 text-xl block mb-1"></i>
                                    <p class="text-xs font-bold text-gray-600">{{ $mInfo['title'] }} belum dilaksanakan</p>
                                    <p class="text-[11px] text-gray-400">Dosen Pemonev belum mengisi evaluasi dan dokumentasi untuk tahap ini.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <i class="fas fa-user-clock text-gray-300 text-2xl mb-2 block"></i>
                        <p class="text-xs text-gray-500 font-medium">Belum ada dosen monev yang ditugaskan untuk kegiatan/program ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
