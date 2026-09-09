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

            <!-- Dosen Monev Card -->
            <div x-data="{ monevImage: null }" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-search-location text-indigo-600"></i>
                        <span>Evaluasi & Monev Lapangan</span>
                    </h3>
                    @if ($dosenMonev && ($dosenMonev->catatan || $dosenMonev->link_monev || !empty($dosenMonev->foto_monev) || !is_null($dosenMonev->nilai)))
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Dievaluasi
                        </span>
                    @elseif ($dosenMonev)
                        <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-full flex items-center gap-1">
                            <i class="fas fa-clock"></i> Belum Evaluasi
                        </span>
                    @endif
                </div>

                @if ($dosenMonev && $dosenMonev->dosen)
                    <div class="space-y-4">
                        <!-- Profil Dosen Monev -->
                        <div class="p-4 bg-indigo-50/60 rounded-2xl border border-indigo-100">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black text-base shadow-sm flex-shrink-0">
                                    {{ substr($dosenMonev->dosen->nama, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-gray-900 text-sm truncate">{{ $dosenMonev->dosen->nama }}</div>
                                    <div class="text-[11px] text-gray-500 font-mono">NIDN: {{ $dosenMonev->dosen->nidn }}</div>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-t border-indigo-200/60 flex items-center justify-between text-xs">
                                <span class="text-indigo-950 font-medium">Tgl Pelaksanaan:</span>
                                <span class="font-bold text-indigo-900">
                                    {{ $dosenMonev->tanggal_monev ? \Carbon\Carbon::parse($dosenMonev->tanggal_monev)->format('d M Y') : '-' }}
                                </span>
                            </div>

                            @if (!is_null($dosenMonev->nilai))
                                <div class="mt-2 pt-2 border-t border-indigo-200/40 flex items-center justify-between">
                                    <span class="text-xs text-indigo-950 font-medium">Nilai Monev:</span>
                                    <span class="px-2.5 py-0.5 bg-indigo-600 text-white font-black text-sm rounded-lg shadow-sm">
                                        {{ $dosenMonev->nilai }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Catatan & Arahan Monev -->
                        @if ($dosenMonev->catatan)
                            <div class="p-4 bg-amber-50/80 border border-amber-200/80 rounded-2xl space-y-1.5">
                                <p class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                                    <i class="fas fa-comment-dots text-amber-600"></i>
                                    <span>Catatan & Masukan Dosen Monev:</span>
                                </p>
                                <div class="text-xs text-amber-950 leading-relaxed whitespace-pre-wrap font-normal">
                                    {{ $dosenMonev->catatan }}
                                </div>
                            </div>
                        @else
                            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                                <p class="text-xs text-gray-400">Belum ada catatan evaluasi dari Dosen Monev.</p>
                            </div>
                        @endif

                        <!-- Google Drive Link Dokumentasi Foto -->
                        @if ($dosenMonev->link_monev)
                            <div class="p-4 bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-emerald-500/10 border border-emerald-200 rounded-2xl space-y-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs shadow-sm">
                                        <i class="fab fa-google-drive"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-emerald-950">Foto Dokumentasi Lapangan</h4>
                                        <p class="text-[10px] text-emerald-700">Tersedia di Google Drive</p>
                                    </div>
                                </div>
                                <a href="{{ $dosenMonev->link_monev }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                    <i class="fab fa-google-drive"></i>
                                    <span>Buka Foto di Google Drive</span>
                                    <i class="fas fa-external-link-alt text-[10px] ml-0.5"></i>
                                </a>
                            </div>
                        @endif

                        <!-- Foto Dokumentasi Hasil Monev (Uploaded directly) -->
                        @if (!empty($dosenMonev->foto_monev) && count($dosenMonev->foto_monev) > 0)
                            <div class="space-y-2">
                                <p class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                    <i class="fas fa-camera text-indigo-600"></i>
                                    <span>Galeri Foto Monev ({{ count($dosenMonev->foto_monev) }} foto):</span>
                                </p>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($dosenMonev->foto_monev as $fIndex => $photo)
                                        @php $url = asset('storage/' . ltrim($photo, '/')); @endphp
                                        <div class="group relative rounded-xl overflow-hidden border border-gray-200 bg-slate-900 aspect-video cursor-pointer shadow-sm" @click="monevImage = '{{ $url }}'">
                                            <img src="{{ $url }}" alt="Dokumentasi Monev" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1">
                                                <i class="fas fa-search-plus"></i> Lihat
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <i class="fas fa-user-clock text-gray-300 text-2xl mb-2 block"></i>
                        <p class="text-xs text-gray-500 font-medium">Belum ada dosen monev yang ditugaskan untuk kegiatan/program Anda.</p>
                    </div>
                @endif

                <!-- Lightbox Modal for Mahasiswa -->
                <div x-show="monevImage" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" style="display: none;" @keydown.escape.window="monevImage = null">
                    <div class="relative max-w-3xl w-full max-h-[85vh] flex flex-col items-center" @click.away="monevImage = null">
                        <button type="button" @click="monevImage = null" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-xl font-bold transition">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                        <img :src="monevImage" class="max-w-full max-h-[80vh] rounded-2xl object-contain shadow-2xl border border-white/20">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
