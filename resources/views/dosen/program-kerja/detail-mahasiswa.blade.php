@extends('layouts.main')

@section('title', 'Program Kerja - ' . $mahasiswa->nama)

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold uppercase tracking-wider mb-2">
                <i class="fas fa-tasks"></i> Detail Program Kerja Mahasiswa
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ $mahasiswa->nama }}</h2>
            <p class="text-gray-500 text-sm mt-1 font-mono">NIM: {{ $mahasiswa->nim }} • {{ $mahasiswa->prodi_full ?? $mahasiswa->prodi }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dosen.program-kerja.semua') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl border border-gray-200 transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="text-sm font-medium">{{ $message }}</span>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
            <span class="text-sm font-medium">{{ $message }}</span>
        </div>
    @endif

    <!-- Mahasiswa Information Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-2">
            <i class="fas fa-user-graduate text-blue-600"></i>
            <span>Informasi Mahasiswa Bimbingan</span>
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">NIM</p>
                <p class="text-base font-bold text-gray-900 font-mono">{{ $mahasiswa->nim }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Program Studi</p>
                <p class="text-base font-bold text-gray-900">{{ $mahasiswa->prodi_full ?? ($mahasiswa->prodi ?? '-') }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Kegiatan Aktif</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-blue-100 text-blue-800 uppercase mt-0.5">
                    {{ $mahasiswa->kegiatan ?? 'Belum Terdaftar' }}
                </span>
            </div>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Penempatan / Lokasi</p>
                @php
                    $kegLower = strtolower($mahasiswa->kegiatan ?? '');
                    $lokasiMhs = match($kegLower) {
                        'kkn' => $mahasiswa->penempatankkn?->lokasikkn?->desa ? 'Desa ' . $mahasiswa->penempatankkn->lokasikkn->desa : null,
                        'ppl' => $mahasiswa->penempatanppl?->lokasippl?->sekolah ?? $mahasiswa->penempatanppl?->lokasippl?->Sekolah ?? $mahasiswa->penempatanppl?->lokasippl?->nama_sekolah,
                        'pkl' => $mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi,
                        'magang' => $mahasiswa->penempatanmagang?->lokasimagang?->nama_instansi,
                        default => null,
                    };
                @endphp
                <p class="text-base font-bold text-gray-900 truncate" title="{{ $lokasiMhs ?? '-' }}">{{ $lokasiMhs ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Program Kerja Individu Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <h3 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-600"></i>
                <span>Program Kerja Individu</span>
                <span class="ml-2 px-2.5 py-0.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-200">
                    {{ $individuPrograms->count() }}
                </span>
            </h3>
        </div>

        @if ($individuPrograms->count() > 0)
            <div class="space-y-6">
                @foreach ($individuPrograms as $program)
                    <div class="border border-gray-200 rounded-3xl p-6 hover:shadow-md transition-all duration-200 bg-white">
                        <!-- Program Title & Status -->
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4 pb-3 border-b border-gray-100">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $program->judul }}</h4>
                                <p class="text-gray-600 text-sm mt-1 leading-relaxed">{{ $program->deskripsi }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider whitespace-nowrap self-start
                                @if($program->status === 'rencana') bg-blue-50 text-blue-700 border border-blue-200
                                @elseif($program->status === 'sedang_berjalan') bg-amber-50 text-amber-700 border border-amber-200
                                @elseif($program->status === 'selesai') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @else bg-gray-100 text-gray-800 border border-gray-200
                                @endif
                            ">
                                {{ str_replace('_', ' ', $program->status) }}
                            </span>
                        </div>

                        <!-- Meta Info: Lokasi & Tanggal -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4 text-xs">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Lokasi</span>
                                <p class="text-gray-900 font-bold text-sm">{{ $program->lokasi ?? '-' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Tanggal Mulai</span>
                                <p class="text-gray-900 font-bold text-sm">{{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Tanggal Selesai</span>
                                <p class="text-gray-900 font-bold text-sm">{{ $program->tanggal_selesai ? $program->tanggal_selesai->format('d M Y') : '-' }}</p>
                            </div>
                        </div>

                        <!-- Catatan & Bimbingan Dosen Pembimbing (Individu) -->
                        <div x-data="{ openForm: {{ $program->catatan_dosen ? 'false' : 'true' }} }" class="mt-4 p-5 rounded-2xl bg-gradient-to-br from-blue-50/70 via-indigo-50/40 to-slate-50 border border-blue-200/80 shadow-sm space-y-3">
                            <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-blue-200/50">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs shadow-sm">
                                        <i class="fas fa-comment-dots"></i>
                                    </div>
                                    <div>
                                        <span class="text-xs font-black text-blue-950 uppercase tracking-wider">Catatan & Arahan Dosen Pembimbing</span>
                                        @if ($program->catatan_dosen_at)
                                            <span class="text-[11px] text-gray-500 font-medium block">
                                                Terakhir disimpan: {{ $program->catatan_dosen_at->format('d M Y, H:i') }} WIB
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" @click="openForm = !openForm" class="px-3 py-1 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 shadow-sm transition">
                                    <i :class="openForm ? 'fa-chevron-up' : 'fa-edit'" class="fas text-[10px]"></i>
                                    <span x-text="openForm ? 'Tutup Form' : '{{ $program->catatan_dosen ? 'Ubah Catatan' : '+ Tulis Catatan' }}'"></span>
                                </button>
                            </div>

                            @if ($program->catatan_dosen)
                                <div x-show="!openForm" class="p-3.5 bg-white rounded-xl border border-blue-100 text-xs text-gray-800 leading-relaxed space-y-1">
                                    <p class="whitespace-pre-wrap pl-3 border-l-2 border-blue-500 text-gray-700 font-medium">{{ $program->catatan_dosen }}</p>
                                </div>
                            @endif

                            <div x-show="openForm" class="pt-1 space-y-3">
                                <form action="{{ route('dosen.program-kerja.catatan', ['type' => 'individu', 'id' => $program->id]) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Catatan / Arahan untuk Mahasiswa:</label>
                                        <textarea name="catatan_dosen" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-blue-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-800" placeholder="Tuliskan catatan perbaikan, persetujuan, revisi, atau arahan tindak lanjut untuk program kerja ini...">{{ old('catatan_dosen', $program->catatan_dosen) }}</textarea>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="flex items-center gap-2">
                                            <label class="text-xs font-bold text-gray-700">Status Program:</label>
                                            <select name="status" class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-500">
                                                <option value="rencana" @selected($program->status === 'rencana')>Rencana</option>
                                                <option value="sedang_berjalan" @selected($program->status === 'sedang_berjalan')>Sedang Berjalan</option>
                                                <option value="selesai" @selected($program->status === 'selesai')>Selesai</option>
                                                <option value="tunda" @selected($program->status === 'tunda')>Tunda</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition inline-flex items-center gap-1.5 self-end sm:self-auto">
                                            <i class="fas fa-save"></i>
                                            <span>Simpan Catatan</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Dosen Monev Evaluation Info (if available) -->
                        @php
                            $monev = $program->monev;
                        @endphp
                        @if ($monev && ($monev->catatan || $monev->nilai !== null || $monev->link_monev || !empty($monev->foto_monev)))
                            <div class="mt-4 p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-3">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-search-location text-indigo-600"></i>
                                        <span class="text-xs font-black text-indigo-950 uppercase tracking-wider">Hasil Evaluasi Monev</span>
                                        @if($monev->dosen)
                                            <span class="text-xs text-indigo-700 font-medium">oleh {{ $monev->dosen->nama }}</span>
                                        @endif
                                    </div>
                                    @if(!is_null($monev->nilai))
                                        <div class="flex items-center gap-1.5 px-3 py-1 bg-indigo-600 text-white font-black text-xs rounded-full shadow-sm">
                                            <i class="fas fa-star text-amber-300 text-[10px]"></i>
                                            <span>Nilai Monev: {{ $monev->nilai }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($monev->tanggal_monev)
                                    <p class="text-[11px] text-gray-500">Tanggal Monev: <strong>{{ \Carbon\Carbon::parse($monev->tanggal_monev)->format('d M Y') }}</strong></p>
                                @endif

                                @if($monev->catatan)
                                    <div class="p-3 bg-white rounded-xl border border-indigo-100 text-xs text-gray-700 leading-relaxed">
                                        <strong class="text-indigo-900 block mb-1">Catatan Evaluasi:</strong>
                                        <p class="whitespace-pre-wrap">{{ $monev->catatan }}</p>
                                    </div>
                                @endif

                                @if($monev->link_monev)
                                    <div>
                                        <a href="{{ $monev->link_monev }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            <i class="fab fa-google-drive"></i>
                                            <span>Buka Dokumentasi di Google Drive</span>
                                            <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                                        </a>
                                    </div>
                                @endif

                                @if(!empty($monev->foto_monev) && count($monev->foto_monev) > 0)
                                    <div class="flex items-center gap-2 overflow-x-auto py-1">
                                        @foreach($monev->foto_monev as $f)
                                            <a href="{{ asset('storage/' . ltrim($f, '/')) }}" target="_blank" class="block w-16 h-16 rounded-xl overflow-hidden border border-indigo-200 bg-black flex-shrink-0 hover:opacity-80 transition">
                                                <img src="{{ asset('storage/' . ltrim($f, '/')) }}" class="w-full h-full object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Luaran / Deliverables for this program -->
                        @if ($program->luarans && $program->luarans->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                    <i class="fas fa-box-open text-blue-600"></i>
                                    <span>Luaran / Deliverables ({{ $program->luarans->count() }})</span>
                                </p>
                                <div class="space-y-2">
                                    @foreach ($program->luarans as $luaran)
                                         <div class="bg-gray-50/80 p-3.5 rounded-2xl border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $luaran->judul }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ ucfirst($luaran->tipe) }} • Progress: <strong class="text-blue-600">{{ $luaran->persentase_selesai }}%</strong></p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                @if ($luaran->file_path)
                                                    <a href="{{ str_starts_with($luaran->file_path, 'http') ? $luaran->file_path : asset('storage/' . $luaran->file_path) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 font-bold inline-flex items-center gap-1">
                                                        <i class="fas fa-paperclip"></i> Berkas
                                                    </a>
                                                @endif
                                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-black uppercase
                                                    @if($luaran->status === 'belum_dikerjakan') bg-red-50 text-red-700 border border-red-200
                                                    @elseif($luaran->status === 'sedang_dikerjakan') bg-amber-50 text-amber-700 border border-amber-200
                                                    @else bg-emerald-50 text-emerald-700 border border-emerald-200
                                                    @endif
                                                ">
                                                    {{ str_replace('_', ' ', $luaran->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl border border-blue-100">
                    <i class="fas fa-inbox"></i>
                </div>
                <h4 class="text-base font-bold text-gray-800">Belum Ada Program Kerja Individu</h4>
                <p class="text-xs text-gray-500 mt-1">Mahasiswa belum menginput program kerja individu di sistem.</p>
            </div>
        @endif
    </div>

    @if (strtolower($mahasiswa->kegiatan ?? '') !== 'pkl')
        <!-- Program Kerja Kelompok Section -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <h3 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fas fa-users text-purple-600"></i>
                <span>Program Kerja Kelompok</span>
                <span class="ml-2 px-2.5 py-0.5 bg-purple-50 text-purple-700 text-xs font-bold rounded-full border border-purple-200">
                    {{ $kelompokPrograms->count() }}
                </span>
            </h3>
        </div>

        @if ($kelompokPrograms->count() > 0)
            <div class="space-y-6">
                @foreach ($kelompokPrograms as $program)
                    <div class="border border-gray-200 rounded-3xl p-6 hover:shadow-md transition-all duration-200 bg-white">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4 pb-3 border-b border-gray-100">
                            <div>
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-black rounded-md uppercase">Kelompok</span>
                                    @if($program->mahasiswaKetua)
                                        <span class="text-xs text-gray-600 font-medium">Ketua: <strong class="text-gray-900">{{ $program->mahasiswaKetua->nama }}</strong> ({{ $program->nim_ketua }})</span>
                                    @endif
                                </div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $program->judul }}</h4>
                                <p class="text-gray-600 text-sm mt-1 leading-relaxed">{{ $program->deskripsi }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider whitespace-nowrap self-start
                                @if($program->status === 'rencana') bg-blue-50 text-blue-700 border border-blue-200
                                @elseif($program->status === 'sedang_berjalan') bg-amber-50 text-amber-700 border border-amber-200
                                @elseif($program->status === 'selesai') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @else bg-gray-100 text-gray-800 border border-gray-200
                                @endif
                            ">
                                {{ str_replace('_', ' ', $program->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4 text-xs">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Lokasi</span>
                                <p class="text-gray-900 font-bold text-sm">{{ $program->lokasi ?? '-' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Tanggal Mulai</span>
                                <p class="text-gray-900 font-bold text-sm">{{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Tanggal Selesai</span>
                                <p class="text-gray-900 font-bold text-sm">{{ $program->tanggal_selesai ? $program->tanggal_selesai->format('d M Y') : '-' }}</p>
                            </div>
                        </div>

                        <!-- Catatan & Bimbingan Dosen Pembimbing (Kelompok) -->
                        <div x-data="{ openForm: {{ $program->catatan_dosen ? 'false' : 'true' }} }" class="mt-4 p-5 rounded-2xl bg-gradient-to-br from-purple-50/70 via-indigo-50/40 to-slate-50 border border-purple-200/80 shadow-sm space-y-3">
                            <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-purple-200/50">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-purple-600 text-white flex items-center justify-center text-xs shadow-sm">
                                        <i class="fas fa-comment-dots"></i>
                                    </div>
                                    <div>
                                        <span class="text-xs font-black text-purple-950 uppercase tracking-wider">Catatan & Arahan Dosen Pembimbing</span>
                                        @if ($program->catatan_dosen_at)
                                            <span class="text-[11px] text-gray-500 font-medium block">
                                                Terakhir disimpan: {{ $program->catatan_dosen_at->format('d M Y, H:i') }} WIB
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" @click="openForm = !openForm" class="px-3 py-1 bg-white hover:bg-purple-50 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 shadow-sm transition">
                                    <i :class="openForm ? 'fa-chevron-up' : 'fa-edit'" class="fas text-[10px]"></i>
                                    <span x-text="openForm ? 'Tutup Form' : '{{ $program->catatan_dosen ? 'Ubah Catatan' : '+ Tulis Catatan' }}'"></span>
                                </button>
                            </div>

                            @if ($program->catatan_dosen)
                                <div x-show="!openForm" class="p-3.5 bg-white rounded-xl border border-purple-100 text-xs text-gray-800 leading-relaxed space-y-1">
                                    <p class="whitespace-pre-wrap pl-3 border-l-2 border-purple-500 text-gray-700 font-medium">{{ $program->catatan_dosen }}</p>
                                </div>
                            @endif

                            <div x-show="openForm" class="pt-1 space-y-3">
                                <form action="{{ route('dosen.program-kerja.catatan', ['type' => 'kelompok', 'id' => $program->id]) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Catatan / Arahan untuk Kelompok Mahasiswa:</label>
                                        <textarea name="catatan_dosen" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-purple-200 text-xs focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-800" placeholder="Tuliskan catatan perbaikan, persetujuan, revisi, atau arahan tindak lanjut untuk kelompok mahasiswa...">{{ old('catatan_dosen', $program->catatan_dosen) }}</textarea>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="flex items-center gap-2">
                                            <label class="text-xs font-bold text-gray-700">Status Program:</label>
                                            <select name="status" class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-purple-500">
                                                <option value="rencana" @selected($program->status === 'rencana')>Rencana</option>
                                                <option value="sedang_berjalan" @selected($program->status === 'sedang_berjalan')>Sedang Berjalan</option>
                                                <option value="selesai" @selected($program->status === 'selesai')>Selesai</option>
                                                <option value="tunda" @selected($program->status === 'tunda')>Tunda</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition inline-flex items-center gap-1.5 self-end sm:self-auto">
                                            <i class="fas fa-save"></i>
                                            <span>Simpan Catatan</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Dosen Monev Evaluation Info (if available) -->
                        @php
                            $monevKel = $program->monev;
                        @endphp
                        @if ($monevKel && ($monevKel->catatan || $monevKel->nilai !== null || $monevKel->link_monev || !empty($monevKel->foto_monev)))
                            <div class="mt-4 p-4 rounded-2xl bg-purple-50/50 border border-purple-100 space-y-3">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-search-location text-purple-600"></i>
                                        <span class="text-xs font-black text-purple-950 uppercase tracking-wider">Hasil Evaluasi Monev Kelompok</span>
                                        @if($monevKel->dosen)
                                            <span class="text-xs text-purple-700 font-medium">oleh {{ $monevKel->dosen->nama }}</span>
                                        @endif
                                    </div>
                                    @if(!is_null($monevKel->nilai))
                                        <div class="flex items-center gap-1.5 px-3 py-1 bg-purple-600 text-white font-black text-xs rounded-full shadow-sm">
                                            <i class="fas fa-star text-amber-300 text-[10px]"></i>
                                            <span>Nilai Monev: {{ $monevKel->nilai }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($monevKel->tanggal_monev)
                                    <p class="text-[11px] text-gray-500">Tanggal Monev: <strong>{{ \Carbon\Carbon::parse($monevKel->tanggal_monev)->format('d M Y') }}</strong></p>
                                @endif

                                @if($monevKel->catatan)
                                    <div class="p-3 bg-white rounded-xl border border-purple-100 text-xs text-gray-700 leading-relaxed">
                                        <strong class="text-purple-900 block mb-1">Catatan Evaluasi:</strong>
                                        <p class="whitespace-pre-wrap">{{ $monevKel->catatan }}</p>
                                    </div>
                                @endif

                                @if($monevKel->link_monev)
                                    <div>
                                        <a href="{{ $monevKel->link_monev }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            <i class="fab fa-google-drive"></i>
                                            <span>Buka Dokumentasi di Google Drive</span>
                                            <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                                        </a>
                                    </div>
                                @endif

                                @if(!empty($monevKel->foto_monev) && count($monevKel->foto_monev) > 0)
                                    <div class="flex items-center gap-2 overflow-x-auto py-1">
                                        @foreach($monevKel->foto_monev as $f)
                                            <a href="{{ asset('storage/' . ltrim($f, '/')) }}" target="_blank" class="block w-16 h-16 rounded-xl overflow-hidden border border-purple-200 bg-black flex-shrink-0 hover:opacity-80 transition">
                                                <img src="{{ asset('storage/' . ltrim($f, '/')) }}" class="w-full h-full object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Catatan Monev (Evaluator Luar) jika ada -->
                            @if ($program->monev && ($program->monev->catatan || $program->monev->nilai || $program->monev->link_monev))
                                <div class="mt-4 p-4 bg-purple-50/60 rounded-2xl border border-purple-100 text-xs space-y-1">
                                    <div class="flex items-center justify-between text-purple-900 font-bold">
                                        <span><i class="fas fa-clipboard-check mr-1"></i> Evaluasi Dosen Pemonev: {{ $program->monev->dosen?->nama ?? '-' }}</span>
                                        @if(!is_null($program->monev->nilai))
                                            <span class="px-2 py-0.5 bg-purple-200 text-purple-900 rounded font-mono font-black">Nilai: {{ $program->monev->nilai }}</span>
                                        @endif
                                    </div>
                                    @if ($program->monev->catatan)
                                        <p class="text-purple-800 italic mt-1 leading-relaxed">"{{ $program->monev->catatan }}"</p>
                                    @endif
                                    @if ($program->monev->link_monev)
                                        <div class="mt-1">
                                            <a href="{{ $program->monev->link_monev }}" target="_blank" class="text-purple-700 hover:underline font-bold inline-flex items-center gap-1">
                                                <i class="fab fa-google-drive"></i> Link Dokumentasi / Foto Monev
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl border border-purple-100">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="text-base font-bold text-gray-800">Belum Ada Program Kerja Kelompok</h4>
                    <p class="text-xs text-gray-500 mt-1">Belum ada program kerja kelompok yang dibuat untuk lokasi penempatan mahasiswa ini.</p>
                </div>
            @endif
        </div>
    @endif

    <!-- All Luaran Overview Section (Individu & Kelompok) -->
    @if ($individuLuarans->count() > 0 || (strtolower($mahasiswa->kegiatan ?? '') !== 'pkl' && $kelompokLuarans->count() > 0))
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8" x-data="{ luaranTab: 'individu' }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                <h3 class="text-xl font-black text-gray-900 flex items-center gap-2">
                    <i class="fas fa-boxes text-blue-600"></i>
                    <span>Semua Luaran / Deliverables</span>
                </h3>
                @if (strtolower($mahasiswa->kegiatan ?? '') !== 'pkl')
                    <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-2xl">
                        <button @click="luaranTab = 'individu'" 
                            :class="luaranTab === 'individu' ? 'bg-white text-blue-700 shadow-sm font-bold' : 'text-gray-600 font-medium hover:text-gray-900'"
                            class="px-4 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5">
                            <i class="fas fa-user text-[10px]"></i>
                            <span>Individu ({{ $individuLuarans->count() }})</span>
                        </button>
                        <button @click="luaranTab = 'kelompok'" 
                            :class="luaranTab === 'kelompok' ? 'bg-white text-purple-700 shadow-sm font-bold' : 'text-gray-600 font-medium hover:text-gray-900'"
                            class="px-4 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5">
                            <i class="fas fa-users text-[10px]"></i>
                            <span>Kelompok ({{ $kelompokLuarans->count() }})</span>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Tab Individu Luaran Table -->
            <div x-show="luaranTab === 'individu'">
                @if($individuLuarans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Judul Luaran</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Program Kerja Individu</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Tipe</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Progress</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($individuLuarans as $luaran)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                            {{ $luaran->judul }}
                                            @if ($luaran->file_path)
                                                <br>
                                                <a href="{{ str_starts_with($luaran->file_path, 'http') ? $luaran->file_path : asset('storage/' . $luaran->file_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 mt-1">
                                                    <i class="fas fa-paperclip"></i> Lihat Berkas
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $luaran->programKerja?->judul ?? '-' }}</td>
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
                @else
                    <p class="text-center py-6 text-sm text-gray-400">Belum ada luaran individu.</p>
                @endif
            </div>

            @if (strtolower($mahasiswa->kegiatan ?? '') !== 'pkl')
                <!-- Tab Kelompok Luaran Table -->
                <div x-show="luaranTab === 'kelompok'" style="display: none;">
                    @if($kelompokLuarans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Judul Luaran</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Program Kerja Kelompok</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Tipe</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Progress</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($kelompokLuarans as $luaran)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                                {{ $luaran->judul }}
                                                @if ($luaran->file_path)
                                                    <br>
                                                    <a href="{{ str_starts_with($luaran->file_path, 'http') ? $luaran->file_path : asset('storage/' . $luaran->file_path) }}" target="_blank" class="text-xs text-purple-600 hover:underline inline-flex items-center gap-1 mt-1">
                                                        <i class="fas fa-paperclip"></i> Lihat Berkas
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $luaran->programKerja?->judul ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($luaran->tipe) }}</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $luaran->persentase_selesai }}%"></div>
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
                    @else
                        <p class="text-center py-6 text-sm text-gray-400">Belum ada luaran kelompok di lokasi ini.</p>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
