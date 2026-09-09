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

    <!-- Program Kerja Kelompok Section (if applicable) -->
    @if (isset($kelompokPrograms) && $kelompokPrograms->count() > 0)
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

        <div class="space-y-6">
            @foreach ($kelompokPrograms as $program)
                <div class="border border-gray-200 rounded-3xl p-6 hover:shadow-md transition-all duration-200 bg-white">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4 pb-3 border-b border-gray-100">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-black rounded-md uppercase">Kelompok</span>
                                @if($program->mahasiswaKetua)
                                    <span class="text-xs text-gray-500">Ketua: <strong>{{ $program->mahasiswaKetua->nama }}</strong> ({{ $program->nim_ketua }})</span>
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

                    <!-- Luaran Kelompok -->
                    @if ($program->luarans && $program->luarans->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                <i class="fas fa-box-open text-purple-600"></i>
                                <span>Luaran Kelompok ({{ $program->luarans->count() }})</span>
                            </p>
                            <div class="space-y-2">
                                @foreach ($program->luarans as $luaran)
                                    <div class="bg-gray-50/80 p-3.5 rounded-2xl border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $luaran->judul }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ ucfirst($luaran->tipe) }} • Progress: <strong class="text-purple-600">{{ $luaran->persentase_selesai }}%</strong></p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if ($luaran->file_path)
                                                <a href="{{ str_starts_with($luaran->file_path, 'http') ? $luaran->file_path : asset('storage/' . $luaran->file_path) }}" target="_blank" class="text-xs text-purple-600 hover:text-purple-800 font-bold inline-flex items-center gap-1">
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
    </div>
    @endif

    <!-- All Luaran Overview Section -->
    @if ($individuLuarans->count() > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-boxes text-blue-600"></i>
                <span>Semua Luaran Mahasiswa ({{ $individuLuarans->count() }})</span>
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Judul Luaran</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Program Kerja</th>
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
        </div>
    @endif
</div>
@endsection
