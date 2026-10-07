@extends('layouts.main')

@php
    $kegiatan = strtoupper($monev->kegiatan ?? 'KKN');
    $pageTitle = $program?->judul ?? ($type === 'individu' ? 'Monev Individu - ' . ($mahasiswa?->nama ?? $monev->nim) : 'Monev Kelompok - ' . ($lokasi?->desa ?? $lokasi?->Sekolah ?? $lokasi?->sekolah ?? $lokasi?->nama_sekolah ?? $lokasi?->nama_instansi ?? 'Kelompok #' . $monev->lokasi_id));
@endphp

@section('title', 'Detail & Hasil Monev - ' . $pageTitle)

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div x-data="{ 
    activeTahap: {{ $activeTahap ?? 1 }}
}" class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 sm:p-10 shadow-xl border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 text-xs font-semibold uppercase tracking-wider">
                    <i class="fas fa-search-location"></i> Evaluasi Lapangan & Monev (3 Tahap)
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white line-clamp-2">{{ $pageTitle }}</h1>
                <p class="text-slate-300 text-sm">
                    Kegiatan: <strong class="text-white uppercase">{{ $kegiatan }}</strong> • Tipe: <strong class="text-white uppercase">{{ $type }}</strong> • Progress: <strong class="text-emerald-400">{{ $monev->monev_selesai_count }}/3 Monev Selesai</strong>
                    @if (!is_null($monev->rata_rata_nilai))
                        • Rata-rata Nilai: <strong class="text-amber-300">{{ $monev->rata_rata_nilai }}</strong>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dosen.program-kerja.monev-dashboard') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl border border-white/20 transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar Monev</span>
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    @if ($message = Session::get('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl shadow-sm">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <span class="font-semibold text-sm">{{ $message }}</span>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl shadow-sm">
            <i class="fas fa-exclamation-circle text-rose-600 text-lg"></i>
            <span class="font-semibold text-sm">{{ $message }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Program Info & Luaran (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Program / Target Details Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        <span>Informasi {{ $type === 'individu' ? 'Mahasiswa' : 'Kelompok / Lokasi' }}</span>
                    </h2>
                    @if ($program)
                        @if ($program->status === 'rencana')
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold rounded-full">Rencana</span>
                        @elseif ($program->status === 'sedang_berjalan')
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">Sedang Berjalan</span>
                        @elseif ($program->status === 'selesai')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">Selesai</span>
                        @else
                            <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">Tunda</span>
                        @endif
                    @endif
                </div>

                <div class="space-y-4 text-sm">
                    @if ($type === 'individu')
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mahasiswa Pelaksana</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $mahasiswa->nama ?? ($program?->mahasiswa?->nama ?? '-') }}</p>
                            <p class="text-xs text-gray-500 font-mono">NIM: {{ $mahasiswa->nim ?? ($program?->nim ?? $monev->nim) }} • {{ $mahasiswa->prodi ?? ($program?->mahasiswa?->prodi ?? '-') }}</p>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kelompok / Lokasi</p>
                            <p class="text-base font-bold text-gray-900 mt-1">
                                {{ $lokasi?->desa ?? $lokasi?->Sekolah ?? $lokasi?->sekolah ?? $lokasi?->nama_sekolah ?? $lokasi?->nama_instansi ?? ($program?->mahasiswaKetua?->nama ?? 'Kelompok #' . $monev->lokasi_id) }}
                            </p>
                            @if(isset($lokasi) && ($lokasi->kecamatan || $lokasi->kabupaten))
                                <p class="text-xs text-gray-500">Kecamatan: {{ $lokasi->kecamatan ?? '-' }} • Kabupaten: {{ $lokasi->kabupaten ?? '-' }}</p>
                            @endif
                        </div>
                    @endif

                    @if ($program)
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-xs text-gray-400 font-medium block">Tgl Mulai</span>
                                <span class="font-bold text-gray-800 text-xs">{{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-xs text-gray-400 font-medium block">Tgl Selesai</span>
                                <span class="font-bold text-gray-800 text-xs">{{ $program->tanggal_selesai ? $program->tanggal_selesai->format('d M Y') : '-' }}</span>
                            </div>
                        </div>

                        @if($program->lokasi)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium block">Lokasi Program</span>
                            <span class="font-bold text-gray-800 text-xs">{{ $program->lokasi }}</span>
                        </div>
                        @endif

                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Deskripsi Program</span>
                            <p class="text-gray-700 text-xs bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed whitespace-pre-wrap">{{ $program->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                    @else
                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200/70 text-xs text-amber-900 leading-relaxed">
                            <i class="fas fa-info-circle mr-1 text-amber-600"></i> Mahasiswa/kelompok ini belum membuat rincian judul program kerja di sistem. Anda tetap dapat melakukan observasi dan mencatat evaluasi monev 3 tahap di formulir sebelah kanan.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Group Members if Kelompok -->
            @if ($type === 'kelompok' && isset($anggota) && count($anggota) > 0)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h3 class="text-base font-black text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-indigo-600"></i>
                    <span>Anggota Kelompok ({{ count($anggota) }})</span>
                </h3>
                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    @foreach ($anggota as $member)
                        <div class="p-3 bg-gray-50 hover:bg-gray-100 border border-gray-100 rounded-xl flex items-center justify-between text-xs">
                            <div class="min-w-0">
                                <p class="font-bold text-gray-900 truncate">{{ $member->nama }}</p>
                                <p class="text-gray-500 font-mono">{{ $member->nim }}</p>
                            </div>
                            @if (isset($program) && $member->nim === $program->nim_ketua)
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 font-bold rounded-md text-[10px]">Ketua</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Deliverables / Luaran Card -->
            @if ($program)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h3 class="text-base font-black text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-box-open text-indigo-600"></i>
                    <span>Luaran / Deliverables ({{ $luarans->count() }})</span>
                </h3>
                @if ($luarans->count() > 0)
                    <div class="space-y-3">
                        @foreach ($luarans as $luaran)
                            <div class="p-4 border border-gray-100 bg-gray-50/50 rounded-2xl">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $luaran->judul }}</h4>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black
                                        @if($luaran->status === 'belum_dikerjakan') bg-gray-100 text-gray-600
                                        @elseif($luaran->status === 'sedang_dikerjakan') bg-amber-100 text-amber-800
                                        @else bg-emerald-100 text-emerald-800
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', strtoupper($luaran->status)) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-2">{{ $luaran->deskripsi }}</p>
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-indigo-600">Progress: {{ $luaran->persentase_selesai }}%</span>
                                    @if ($luaran->file_path)
                                        <a href="{{ asset('storage/' . $luaran->file_path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                            <i class="fas fa-paperclip"></i> Berkas Luaran
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada luaran yang ditambahkan.</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Column: 3-Stage Monev Tabs & Forms (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Stage Selector Tabs -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-2.5 flex flex-wrap sm:flex-nowrap gap-2">
                @php
                    $tahapList = [
                        1 => ['title' => 'Monev 1', 'sub' => 'Tahap Awal', 'data' => $tahap1],
                        2 => ['title' => 'Monev 2', 'sub' => 'Tahap Progres/Tengah', 'data' => $tahap2],
                        3 => ['title' => 'Monev 3', 'sub' => 'Tahap Evaluasi Akhir', 'data' => $tahap3],
                    ];
                @endphp

                @foreach ($tahapList as $ke => $tInfo)
                    @php 
                        $tData = $tInfo['data'];
                        $isFilled = $tData->exists && $tData->is_filled;
                    @endphp
                    <button type="button" 
                        @click="activeTahap = {{ $ke }}" 
                        :class="activeTahap === {{ $ke }} ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200/70'"
                        class="flex-1 py-3 px-4 rounded-2xl font-bold text-left transition-all duration-200 relative overflow-hidden group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-wider font-black">{{ $tInfo['title'] }}</span>
                            @if ($isFilled)
                                <span :class="activeTahap === {{ $ke }} ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800'" class="px-2 py-0.5 rounded-full text-[10px] font-bold">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </span>
                            @else
                                <span :class="activeTahap === {{ $ke }} ? 'bg-white/10 text-indigo-200' : 'bg-gray-200/80 text-gray-500'" class="px-2 py-0.5 rounded-full text-[10px] font-medium">
                                    Belum
                                </span>
                            @endif
                        </div>
                        <p :class="activeTahap === {{ $ke }} ? 'text-indigo-100' : 'text-gray-500'" class="text-[11px] font-normal truncate mt-0.5">
                            {{ $tInfo['sub'] }}
                            @if ($isFilled && !is_null($tData->nilai))
                                • Nilai: <strong>{{ $tData->nilai }}</strong>
                            @endif
                        </p>
                    </button>
                @endforeach
            </div>

            <!-- Loop over the 3 stages forms -->
            @foreach ($tahapList as $ke => $tInfo)
                @php 
                    $tData = $tInfo['data'];
                    $tabelName = $tInfo['title'] . ' (' . $tInfo['sub'] . ')';
                @endphp

                <div x-show="activeTahap === {{ $ke }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <!-- Monev Review Form for Stage {{ $ke }} -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                                      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-black uppercase mb-1">
                                    <i class="fas fa-layer-group"></i> {{ $tInfo['title'] }} - {{ $tInfo['sub'] }}
                                </div>
                                <h2 class="text-xl font-black text-gray-900">Form Evaluasi {{ $tInfo['title'] }}</h2>
                                <p class="text-xs text-gray-500 mt-0.5">Masukkan catatan evaluasi lapangan, nilai monev, dan tautan Google Drive dokumentasi untuk {{ $tInfo['title'] }}.</p>
                            </div>
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                        </div>

                        <form action="{{ route('dosen.program-kerja.monev-nilai-id', $monev->id) }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="tahap_ke" value="{{ $ke }}">

                            <!-- Tanggal Pelaksanaan Monev -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Tanggal Pelaksanaan {{ $tInfo['title'] }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_monev" value="{{ old('tanggal_monev', $tData->tanggal_monev ? \Carbon\Carbon::parse($tData->tanggal_monev)->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <!-- Catatan Monev / Hasil Pengamatan -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Catatan & Hasil Evaluasi {{ $tInfo['title'] }} <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-400 mb-2">
                                    @if ($ke == 1)
                                        Fokus Tahap 1: Pengamatan awal lokasi, penerimaan warga/mitra, rencana program kerja & kesiapan pelaksanaan.
                                    @elseif ($ke == 2)
                                        Fokus Tahap 2: Evaluasi progres pelaksanaan program kerja, kendala yang dihadapi, dan arahan penyelesaian.
                                    @else
                                        Fokus Tahap 3: Evaluasi akhir pencapaian luaran/program kerja, dampak kegiatan, dan penutupan/penarikan.
                                    @endif
                                </p>
                                <textarea name="catatan" rows="5" placeholder="Tuliskan hasil monitoring dan catatan evaluasi untuk {{ $tInfo['title'] }}..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition leading-relaxed">{{ old('catatan', $tData->catatan) }}</textarea>
                            </div>

                            <!-- Nilai Monev (Opsional) -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Nilai {{ $tInfo['title'] }} (Opsional, Skala 0 - 100)
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.1" min="0" max="100" name="nilai" value="{{ old('nilai', $tData->nilai) }}" placeholder="Contoh: 88.5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                        / 100
                                    </div>
                                </div>
                            </div>

                            <!-- Link Google Drive Dokumentasi Foto Monev -->
                            <div class="p-5 bg-gradient-to-br from-indigo-50/70 via-blue-50/40 to-emerald-50/40 rounded-2xl border border-indigo-100/80 space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                                        <i class="fab fa-google-drive text-emerald-600 text-base"></i>
                                        <span>Tautan Google Drive Dokumentasi Foto {{ $tInfo['title'] }}</span>
                                    </label>
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full uppercase tracking-wider">
                                        Google Drive
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    Masukkan tautan folder atau file <strong>Google Drive</strong> khusus dokumentasi foto/kegiatan {{ $tInfo['title'] }}. Mahasiswa dan admin dapat langsung membuka tautan tersebut.
                                </p>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600">
                                        <i class="fab fa-google-drive text-base"></i>
                                    </div>
                                    <input type="url" name="link_monev" value="{{ old('link_monev', $tData->link_monev) }}" placeholder="https://drive.google.com/drive/folders/..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                                </div>

                                @if ($tData->link_monev)
                                    <div class="pt-1 flex items-center justify-between">
                                        <span class="text-xs text-emerald-800 font-medium truncate max-w-md">
                                            <i class="fas fa-check-circle text-emerald-600 mr-1"></i> Tautan aktif tersimpan
                                        </span>
                                        <a href="{{ $tData->link_monev }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            <i class="fab fa-google-drive"></i>
                                            <span>Buka Drive {{ $tInfo['title'] }}</span>
                                            <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                                <span class="text-xs text-gray-400 font-medium">
                                    Menyimpan data evaluasi untuk <strong>{{ $tInfo['title'] }}</strong>
                                </span>
                                <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i>
                                    <span>Simpan {{ $tInfo['title'] }}</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Saved Photos & Drive Gallery for Stage {{ $ke }} -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div>
                                <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                    <i class="fab fa-google-drive text-emerald-600"></i>
                                    <span>Dokumentasi Google Drive ({{ $tInfo['title'] }})</span>
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Dokumentasi foto monitoring dan evaluasi untuk {{ $tInfo['title'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Google Drive Link Preview if available -->
                        @if ($tData->link_monev)
                            <div class="p-5 bg-gradient-to-r from-emerald-500/10 via-teal-500/5 to-transparent border border-emerald-200 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-500/20 flex-shrink-0">
                                        <i class="fab fa-google-drive"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-black text-gray-900 text-sm">Folder Drive {{ $tInfo['title'] }}</h4>
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-md">Tersambung</span>
                                        </div>
                                        <p class="text-xs text-gray-500 truncate max-w-md mt-0.5 font-mono">{{ $tData->link_monev }}</p>
                                    </div>
                                </div>
                                <a href="{{ $tData->link_monev }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition whitespace-nowrap">
                                    <i class="fab fa-google-drive"></i>
                                    <span>Buka di Google Drive</span>
                                    <i class="fas fa-external-link-alt text-[10px]"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                <div class="w-12 h-12 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-3">
                                    <i class="fab fa-google-drive"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-700">Belum ada tautan Google Drive untuk {{ $tInfo['title'] }}.</p>
                                <p class="text-[11px] text-gray-400 mt-1">Masukkan tautan folder Google Drive pada formulir di atas untuk membagikan dokumentasi foto monev.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

