@extends('layouts.admin')

@section('title', 'Plotting Dosen Penguji - ' . strtoupper($selectedKegiatan))

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 sm:p-10 shadow-xl border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 text-xs font-semibold uppercase tracking-wider">
                    <i class="fas fa-gavel"></i> Ujian Akhir & Evaluasi
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Plotting Dosen Penguji {{ strtoupper($selectedKegiatan) }}
                </h1>
                <p class="text-slate-300 text-sm max-w-2xl">
                    Tugaskan dosen sebagai penguji ujian akhir mahasiswa peserta kegiatan <strong>{{ strtoupper($selectedKegiatan) }}</strong> secara <strong>Perorangan (Individu)</strong> maupun <strong>Kelompok / Lokasi Penempatan</strong>.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admindashboard') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl border border-white/20 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Dashboard Admin
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3 text-lg text-emerald-600"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm font-bold flex items-center shadow-sm">
        <i class="fas fa-exclamation-triangle mr-3 text-lg text-rose-600"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Import CSV Plotting -->
    @include('admin._import_plotting', [
        'importRoute' => route('assign.dosenpenguji.import'),
        'label' => 'Plotting Dosen Penguji',
        'formatInfo' => 'nim, nidn',
        'color' => 'indigo',
    ])

    <!-- Filter Tabs (Kegiatan Tabs & Type Selector seperti di Dosen Monev) -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Kegiatan Tabs (KKN, PPL, PKL, Magang) -->
        <div class="flex flex-wrap items-center gap-2">
            @foreach($allowedKegiatan as $k)
                <a href="?kegiatan={{ $k }}&type={{ $selectedType }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ strtoupper($selectedKegiatan) === strtoupper($k) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <i class="fas 
                        @if(strtoupper($k) === 'KKN') fa-hands-helping
                        @elseif(strtoupper($k) === 'PPL') fa-chalkboard-teacher
                        @elseif(strtoupper($k) === 'PKL') fa-briefcase
                        @else fa-building
                        @endif"></i>
                    <span>{{ strtoupper($k) }}</span>
                </a>
            @endforeach
        </div>

        <!-- Type Selector (Individu vs Kelompok) -->
        <div class="inline-flex p-1 bg-gray-100 rounded-xl border border-gray-200/60 shadow-inner">
            <a href="?kegiatan={{ $selectedKegiatan }}&type=perorangan" 
               class="px-5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $selectedType === 'perorangan' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                <i class="fas fa-user {{ $selectedType === 'perorangan' ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span>Mahasiswa (Perorangan)</span>
                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-black {{ $selectedType === 'perorangan' ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-200 text-gray-600' }}">
                    {{ $mahasiswas->count() }}
                </span>
            </a>
            <a href="?kegiatan={{ $selectedKegiatan }}&type=kelompok" 
               class="px-5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $selectedType === 'kelompok' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                <i class="fas fa-users {{ $selectedType === 'kelompok' ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span>Kelompok / Lokasi</span>
                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-black {{ $selectedType === 'kelompok' ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-200 text-gray-600' }}">
                    {{ $kelompoks->count() }}
                </span>
            </a>
        </div>
    </div>

    <!-- Assignment Form Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <form id="assignDosenPengujiForm" action="{{ route('assign.dosenpenguji.store') }}" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="kegiatan" value="{{ $selectedKegiatan }}">
            <input type="hidden" name="type" value="{{ $selectedType }}">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- ==================== LEFT COLUMN: SELECTION LIST ==================== -->
                <div class="lg:col-span-7 space-y-4">
                    
                    @if($selectedType === 'perorangan')
                        <!-- MODE PERORANGAN (INDIVIDU) -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-2">
                            <div>
                                <h3 class="text-sm font-black text-gray-900 tracking-tight flex items-center gap-2">
                                    <i class="fas fa-user-graduate text-indigo-600"></i>
                                    Daftar Mahasiswa Peserta {{ strtoupper($selectedKegiatan) }} (Belum Ada Penguji)
                                </h3>
                                <p class="text-xs text-gray-400 mt-0.5">Pilih mahasiswa yang akan ditugaskan ke dosen penguji.</p>
                            </div>
                            @if($mahasiswas->isNotEmpty())
                            <button type="button" id="selectAllPeroranganBtn" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1 self-start sm:self-auto">
                                <i class="fas fa-check-double"></i> <span>Pilih Semua</span>
                            </button>
                            @endif
                        </div>

                        <!-- Live Search Input -->
                        @if($mahasiswas->isNotEmpty())
                        <div class="relative">
                            <input type="text" id="searchPeroranganInput" placeholder="Cari nama, NIM, prodi, atau lokasi penempatan {{ strtoupper($selectedKegiatan) }}..." 
                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <i class="fas fa-search text-xs"></i>
                            </div>
                            <button type="button" id="clearSearchPeroranganBtn" class="hidden absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times-circle text-xs"></i>
                            </button>
                        </div>
                        @endif

                        <!-- Student List -->
                        <div class="bg-slate-50 rounded-3xl border border-gray-100 overflow-hidden">
                            @if($mahasiswas->isNotEmpty())
                            <div class="max-h-[460px] overflow-y-auto sidebar-scroll p-4 space-y-2.5" id="peroranganListContainer">
                                @foreach($mahasiswas as $mahasiswa)
                                    @php
                                        $lokasiName = match(strtoupper($selectedKegiatan)) {
                                            'KKN' => $mahasiswa->penempatankkn?->lokasikkn ? 'Desa ' . $mahasiswa->penempatankkn->lokasikkn->desa : null,
                                            'PPL' => $mahasiswa->penempatanppl?->lokasippl ? ($mahasiswa->penempatanppl->lokasippl->Sekolah ?? $mahasiswa->penempatanppl->lokasippl->sekolah ?? $mahasiswa->penempatanppl->lokasippl->nama_sekolah) : null,
                                            'PKL' => $mahasiswa->penempatanpkl?->lokasipkl ? $mahasiswa->penempatanpkl->lokasipkl->nama_instansi : null,
                                            'MAGANG' => $mahasiswa->penempatanmagang?->lokasimagang ? $mahasiswa->penempatanmagang->lokasimagang->nama_instansi : null,
                                            default => null,
                                        };
                                        $searchable = strtolower($mahasiswa->nama . ' ' . $mahasiswa->nim . ' ' . $mahasiswa->prodi . ' ' . ($lokasiName ?? ''));
                                    @endphp
                                    <label class="student-item-card flex items-start p-4 bg-white border border-gray-100 rounded-2xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/40 hover:shadow-sm transition-all group" data-search="{{ $searchable }}">
                                        <div class="relative flex items-center justify-center pt-0.5">
                                            <input type="checkbox" name="nims[]" value="{{ $mahasiswa->nim }}" class="student-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500 transition-all cursor-pointer">
                                        </div>
                                        <div class="ml-3.5 flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <p class="text-sm font-bold text-gray-800 group-hover:text-indigo-700 transition-colors truncate">{{ $mahasiswa->nama }}</p>
                                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-md text-[10px] font-black uppercase tracking-wider shrink-0">
                                                    {{ strtoupper($selectedKegiatan) }}
                                                </span>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1 text-[11px]">
                                                <span class="font-mono font-bold text-gray-400">{{ $mahasiswa->nim }}</span>
                                                <span class="text-gray-300">•</span>
                                                <span class="text-gray-500">{{ $mahasiswa->prodi }}</span>
                                            </div>
                                            <div class="mt-2 flex items-center gap-2">
                                                @if($lokasiName)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-md text-[10px] font-bold truncate">
                                                        <i class="fas fa-map-marker-alt text-rose-500 text-[10px]"></i>
                                                        <span class="truncate">{{ $lokasiName }}</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-100 rounded-md text-[10px] font-bold">
                                                        <i class="fas fa-exclamation-circle text-amber-500 text-[10px]"></i> Belum ada penempatan lokasi
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach

                                <div id="noResultsPerorangan" class="hidden p-8 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                                    <i class="fas fa-search text-gray-300 text-2xl mb-2"></i>
                                    <p class="text-xs font-bold text-gray-500">Tidak ada mahasiswa yang cocok dengan kata kunci pencarian.</p>
                                </div>
                            </div>
                            @else
                            <div class="p-10 text-center">
                                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-emerald-500 text-2xl">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-800">Semua Mahasiswa {{ strtoupper($selectedKegiatan) }} Sudah Di-Plot</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Seluruh mahasiswa peserta {{ strtoupper($selectedKegiatan) }} saat ini sudah memiliki dosen penguji.</p>
                            </div>
                            @endif
                        </div>

                    @else
                        <!-- MODE KELOMPOK (LOKASI PENEMPATAN) -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-2">
                            <div>
                                <h3 class="text-sm font-black text-gray-900 tracking-tight flex items-center gap-2">
                                    <i class="fas fa-layer-group text-indigo-600"></i>
                                    Daftar Kelompok / Lokasi {{ strtoupper($selectedKegiatan) }}
                                </h3>
                                <p class="text-xs text-gray-400 mt-0.5">Pilih kelompok/lokasi untuk mem-plot semua anggotanya ke dosen penguji.</p>
                            </div>
                            @if($kelompoks->isNotEmpty())
                            <button type="button" id="selectAllKelompokBtn" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1 self-start sm:self-auto">
                                <i class="fas fa-check-double"></i> <span>Pilih Semua Kelompok</span>
                            </button>
                            @endif
                        </div>

                        <!-- Live Search Input -->
                        @if($kelompoks->isNotEmpty())
                        <div class="relative">
                            <input type="text" id="searchKelompokInput" placeholder="Cari nama kelompok, desa, sekolah, instansi, atau wilayah {{ strtoupper($selectedKegiatan) }}..." 
                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <i class="fas fa-search text-xs"></i>
                            </div>
                            <button type="button" id="clearSearchKelompokBtn" class="hidden absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times-circle text-xs"></i>
                            </button>
                        </div>
                        @endif

                        <!-- Group List Cards with Accordion -->
                        <div class="bg-slate-50 rounded-3xl border border-gray-100 overflow-hidden">
                            @if($kelompoks->isNotEmpty())
                            <div class="max-h-[480px] overflow-y-auto sidebar-scroll p-4 space-y-3" id="kelompokListContainer">
                                @foreach($kelompoks as $grp)
                                    @php
                                        $grpSearchable = strtolower($grp->nama . ' ' . $grp->detail);
                                        $isDisabled = ($grp->total_count === 0 || $grp->is_fully_assigned);
                                    @endphp
                                    <div class="group-card-item bg-white border border-gray-100 hover:border-indigo-200 rounded-2xl overflow-hidden transition-all shadow-sm" data-search="{{ $grpSearchable }}" id="card-{{ $grp->id }}">
                                        <!-- Group Card Header -->
                                        <div class="p-4 flex items-start gap-3">
                                            <div class="pt-0.5">
                                                <input type="checkbox" 
                                                       name="kelompok_ids[]" 
                                                       value="{{ $grp->id }}" 
                                                       data-group-id="{{ $grp->id }}"
                                                       class="group-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500 cursor-pointer disabled:opacity-40"
                                                       {{ $isDisabled ? 'disabled' : '' }}>
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2">
                                                    <div class="flex items-center gap-2">
                                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider
                                                            @if($grp->kegiatan === 'KKN') bg-blue-50 text-blue-700 border border-blue-100
                                                            @elseif($grp->kegiatan === 'PPL') bg-emerald-50 text-emerald-700 border border-emerald-100
                                                            @elseif($grp->kegiatan === 'PKL') bg-purple-50 text-purple-700 border border-purple-100
                                                            @else bg-amber-50 text-amber-700 border border-amber-100
                                                            @endif">
                                                            {{ $grp->kegiatan }}
                                                        </span>
                                                        <h4 class="text-sm font-black text-gray-800 truncate">{{ $grp->nama }}</h4>
                                                    </div>
                                                    
                                                    <!-- Status Badge -->
                                                    <div>
                                                        @if($grp->is_empty)
                                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-md text-[10px] font-bold">
                                                                Kosong
                                                            </span>
                                                        @elseif($grp->is_fully_assigned)
                                                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold flex items-center gap-1">
                                                                <i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> Semua Ter-plot ({{ $grp->total_count }} mhs)
                                                            </span>
                                                        @else
                                                            <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-md text-[10px] font-bold flex items-center gap-1">
                                                                <i class="fas fa-clock text-indigo-500 text-[10px]"></i> {{ $grp->unassigned_count }} Belum Diplot
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <p class="text-xs text-gray-400 mt-1 truncate">
                                                    <i class="fas fa-map-pin text-[10px] mr-1 text-gray-400"></i>{{ $grp->detail ?: 'Lokasi penempatan' }}
                                                </p>

                                                <!-- Accordion Toggle Bar -->
                                                <div class="mt-3 pt-2.5 border-t border-gray-50 flex items-center justify-between text-xs">
                                                    <div class="flex items-center gap-2 text-[11px] text-gray-500">
                                                        <span><strong class="text-gray-800">{{ $grp->total_count }}</strong> Mahasiswa</span>
                                                        <span>•</span>
                                                        <span class="text-indigo-600 font-bold">{{ $grp->unassigned_count }} siap di-plot</span>
                                                    </div>

                                                    <button type="button" class="accordion-toggle text-[11px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1" data-target="accordion-body-{{ $grp->id }}">
                                                        <span>Lihat Anggota</span>
                                                        <i class="fas fa-chevron-down transition-transform duration-200 text-[10px]"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Accordion Member List (Hidden by default) -->
                                        <div id="accordion-body-{{ $grp->id }}" class="hidden bg-slate-50/80 border-t border-gray-100 p-3 space-y-2">
                                            @if($grp->members->isNotEmpty())
                                                <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 sidebar-scroll">
                                                    @foreach($grp->members as $member)
                                                        @php
                                                            $hasPenguji = (bool)$member->dosenPenguji;
                                                        @endphp
                                                        <label class="flex items-center justify-between p-2.5 bg-white border border-gray-100 rounded-xl text-xs {{ $hasPenguji ? 'opacity-60 bg-gray-50' : 'cursor-pointer hover:border-indigo-200' }}">
                                                            <div class="flex items-center gap-2.5 min-w-0">
                                                                <input type="checkbox" 
                                                                       name="nims[]" 
                                                                       value="{{ $member->nim }}" 
                                                                       class="student-checkbox group-member-checkbox-{{ $grp->id }} w-3.5 h-3.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                                       data-group="{{ $grp->id }}"
                                                                       {{ $hasPenguji ? 'disabled' : '' }}>
                                                                <div class="truncate">
                                                                    <p class="font-bold text-gray-800 text-xs truncate">{{ $member->nama }}</p>
                                                                    <p class="text-[10px] text-gray-400 font-mono">{{ $member->nim }} • {{ $member->prodi }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="shrink-0 pl-2">
                                                                @if($hasPenguji)
                                                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded text-[9px] font-bold" title="Penguji: {{ $member->dosenPenguji->dosen?->nama ?? '-' }}">
                                                                        <i class="fas fa-check mr-0.5"></i> {{ $member->dosenPenguji->dosen?->nama ?? 'Ada Penguji' }}
                                                                    </span>
                                                                @else
                                                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-100 rounded text-[9px] font-bold">
                                                                        Belum Ada Penguji
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-[11px] text-gray-400 text-center py-2">Belum ada mahasiswa yang ditempatkan di kelompok/lokasi ini.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <div id="noResultsKelompok" class="hidden p-8 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                                    <i class="fas fa-search text-gray-300 text-2xl mb-2"></i>
                                    <p class="text-xs font-bold text-gray-500">Tidak ada kelompok yang cocok dengan kata kunci pencarian.</p>
                                </div>
                            </div>
                            @else
                            <div class="p-10 text-center">
                                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-indigo-500 text-2xl">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-800">Belum Ada Data Kelompok / Lokasi {{ strtoupper($selectedKegiatan) }}</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Pastikan data lokasi penempatan mahasiswa kegiatan {{ strtoupper($selectedKegiatan) }} sudah diisi di menu Master Lokasi.</p>
                            </div>
                            @endif
                        </div>
                    @endif

                </div>

                <!-- ==================== RIGHT COLUMN: DOSEN SELECT & SUBMIT ==================== -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Dosen Selection Box -->
                    <div class="bg-gradient-to-br from-indigo-50/50 via-white to-slate-50 p-6 rounded-3xl border border-indigo-100 shadow-sm space-y-4">
                        <div>
                            <label class="text-[11px] font-black text-indigo-900 uppercase tracking-[0.15em] block mb-2">
                                <i class="fas fa-user-tie mr-1 text-indigo-600"></i> Pilih Dosen Penguji <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="nidn" required class="w-full pl-5 pr-10 py-4 bg-white border border-gray-200 rounded-2xl text-gray-800 font-bold text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/15 focus:border-indigo-600 appearance-none shadow-sm transition-all">
                                    <option value="">-- Pilih Nama Dosen Penguji --</option>
                                    @foreach($dosens as $dosen)
                                        <option value="{{ $dosen->nidn }}">
                                            {{ $dosen->nama }} (NIDN: {{ $dosen->nidn }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-indigo-600">
                                    <i class="fas fa-user-shield text-sm"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Counter Display -->
                        <div class="p-4 bg-indigo-600/5 rounded-2xl border border-indigo-100/80 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-md shadow-indigo-200">
                                    <i class="fas fa-users-viewfinder"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-wider text-indigo-900">Total Terpilih</p>
                                    <p class="text-xs font-bold text-gray-500" id="selectedSummarySubtitle">0 item dipilih</p>
                                </div>
                            </div>
                            <span id="selectedCountBadge" class="px-3 py-1 bg-indigo-600 text-white rounded-full text-xs font-black shadow-sm">
                                0 Mahasiswa
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" id="submitPlottingBtn" class="w-full py-4.5 px-6 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-black rounded-2xl shadow-xl shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all flex items-center justify-center space-x-3 group">
                                <i class="fas fa-gavel group-hover:rotate-12 transition-transform text-sm"></i>
                                <span class="uppercase tracking-widest text-xs font-black">Simpan Plotting Penguji</span>
                            </button>
                        </div>
                    </div>

                    <!-- Helpful Tips Box -->
                    <div class="bg-slate-50 p-5 rounded-3xl border border-gray-100 text-xs text-gray-600 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-gray-800">
                            <i class="fas fa-lightbulb text-amber-500 text-sm"></i>
                            <span>Panduan Plotting Cepat</span>
                        </div>
                        <ul class="space-y-1.5 text-[11px] text-gray-500 list-disc list-inside leading-relaxed">
                            <li>Pilih program kegiatan di bagian atas (<strong>KKN</strong>, <strong>PPL</strong>, <strong>PKL</strong>, <strong>Magang</strong>).</li>
                            <li>Gunakan mode <strong>Kelompok</strong> untuk menugaskan satu dosen penguji ke seluruh mahasiswa di satu lokasi/kelompok penempatan.</li>
                            <li>Gunakan mode <strong>Perorangan</strong> jika ingin menugaskan dosen penguji secara khusus per mahasiswa.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- ==================== ASSIGNMENTS LIST (HASIL PLOTTING) ==================== -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-50 bg-slate-50/40">
            <div>
                <span class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-1">Data Hasil Penugasan {{ strtoupper($selectedKegiatan) }}</span>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Daftar Mahasiswa & Dosen Penguji</h3>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-black border border-indigo-100 flex items-center gap-1.5">
                    <i class="fas fa-check-circle text-indigo-500"></i>
                    {{ $assignments->count() }} Plotting Aktif
                </span>
            </div>
        </div>

        <div class="overflow-x-auto p-8">
            <table class="w-full text-left border-separate border-spacing-0" id="assignmentsTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/70 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Mahasiswa</th>
                        <th class="px-6 py-4 bg-slate-50/70 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kegiatan</th>
                        <th class="px-6 py-4 bg-slate-50/70 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Lokasi / Kelompok</th>
                        <th class="px-6 py-4 bg-slate-50/70 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Dosen Penguji</th>
                        <th class="px-6 py-4 bg-slate-50/70 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Nilai Ujian</th>
                        <th class="px-6 py-4 bg-slate-50/70 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assignments as $assignment)
                    @php
                        $mhs = $assignment->mahasiswa;
                        $lokasiPlacement = match(strtoupper($mhs?->kegiatan ?? '')) {
                            'KKN' => $mhs->penempatankkn?->lokasikkn ? 'Desa ' . $mhs->penempatankkn->lokasikkn->desa : null,
                            'PPL' => $mhs->penempatanppl?->lokasippl ? ($mhs->penempatanppl->lokasippl->Sekolah ?? $mhs->penempatanppl->lokasippl->sekolah ?? $mhs->penempatanppl->lokasippl->nama_sekolah) : null,
                            'PKL' => $mhs->penempatanpkl?->lokasipkl ? $mhs->penempatanpkl->lokasipkl->nama_instansi : null,
                            'MAGANG' => $mhs->penempatanmagang?->lokasimagang ? $mhs->penempatanmagang->lokasimagang->nama_instansi : null,
                            default => null,
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/40 transition-colors group">
                        <!-- Mahasiswa -->
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0 border border-indigo-100/50">
                                    {{ substr($mhs?->nama ?? 'M', 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $mhs?->nama ?? '-' }}</p>
                                    <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-mono mt-0.5">
                                        <span>{{ $assignment->nim }}</span>
                                        <span>•</span>
                                        <span class="text-gray-500 font-sans truncate">{{ $mhs?->prodi ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Kegiatan -->
                        <td class="px-6 py-5">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                {{ $mhs?->kegiatan ?? '-' }}
                            </span>
                        </td>

                        <!-- Lokasi / Kelompok -->
                        <td class="px-6 py-5">
                            @if($lokasiPlacement)
                                <div class="flex items-center space-x-1.5 text-xs text-gray-700 font-semibold">
                                    <i class="fas fa-map-marker-alt text-rose-500 text-[10px]"></i>
                                    <span class="truncate">{{ $lokasiPlacement }}</span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum Ada Lokasi</span>
                            @endif
                        </td>

                        <!-- Dosen Penguji -->
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-2 text-indigo-700">
                                <i class="fas fa-user-shield text-xs text-indigo-500"></i>
                                <div class="min-w-0">
                                    <span class="text-sm font-bold block truncate">{{ $assignment->dosen?->nama ?? '-' }}</span>
                                    <span class="text-[10px] text-indigo-500 font-mono">NIDN: {{ $assignment->nidn }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Nilai Ujian -->
                        <td class="px-6 py-5 text-center">
                            @if(!is_null($assignment->nilai))
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-black">
                                    {{ $assignment->nilai }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-lg text-[10px] font-bold">
                                    Belum Diuji
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('assign.dosenpenguji.delete', $assignment->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus plotting penguji untuk mahasiswa {{ $mhs?->nama ?? $assignment->nim }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100" title="Hapus Plotting">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-xs text-gray-400">
                            Belum ada data plotting dosen penguji untuk kegiatan {{ strtoupper($selectedKegiatan) }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        if ($.fn.DataTable.isDataTable('#assignmentsTable')) {
            $('#assignmentsTable').DataTable().destroy();
        }
        $('#assignmentsTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari data hasil plotting..."
            },
            "order": [[0, 'asc']],
            "pageLength": 10,
            "dom": '<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-0 py-4 gap-4"lf>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-0 py-4 gap-4"ip>'
        });

        // ----------------------------------------------------
        // SELECTION COUNTER LOGIC
        // ----------------------------------------------------
        function updateSelectedCounter() {
            let totalStudentsSelected = 0;

            const mode = "{{ $selectedType }}";

            if (mode === 'perorangan') {
                const checkedStudents = document.querySelectorAll('.student-checkbox:checked');
                totalStudentsSelected = checkedStudents.length;
            } else {
                const countedNims = new Set();

                // 1. Group checkboxes checked
                document.querySelectorAll('.group-checkbox:checked').forEach(gcb => {
                    const groupId = gcb.getAttribute('data-group-id');
                    document.querySelectorAll('.group-member-checkbox-' + groupId + ':not(:disabled)').forEach(mcb => {
                        countedNims.add(mcb.value);
                    });
                });

                // 2. Individual member checkboxes checked inside accordion
                document.querySelectorAll('.group-member-checkbox:checked:not(:disabled)').forEach(mcb => {
                    countedNims.add(mcb.value);
                });

                totalStudentsSelected = countedNims.size;
            }

            const badge = document.getElementById('selectedCountBadge');
            const subtitle = document.getElementById('selectedSummarySubtitle');

            if (badge) {
                badge.textContent = totalStudentsSelected + ' Mahasiswa';
                if (totalStudentsSelected > 0) {
                    badge.classList.remove('bg-indigo-600');
                    badge.classList.add('bg-emerald-600');
                } else {
                    badge.classList.remove('bg-emerald-600');
                    badge.classList.add('bg-indigo-600');
                }
            }

            if (subtitle) {
                subtitle.textContent = totalStudentsSelected + ' mahasiswa siap di-plot';
            }
        }

        // ----------------------------------------------------
        // ROBUST LIVE SEARCH (PERORANGAN)
        // ----------------------------------------------------
        const searchPeroranganInput = document.getElementById('searchPeroranganInput');
        const clearSearchPeroranganBtn = document.getElementById('clearSearchPeroranganBtn');
        const noResultsPerorangan = document.getElementById('noResultsPerorangan');

        function filterPerorangan(term) {
            term = (term || '').toLowerCase().trim();
            const cards = document.querySelectorAll('.student-item-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const searchData = ((card.getAttribute('data-search') || '') + ' ' + card.innerText).toLowerCase();
                const isMatch = term === '' || searchData.includes(term);
                if (isMatch) {
                    card.style.setProperty('display', 'flex', 'important');
                    visibleCount++;
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            });

            if (clearSearchPeroranganBtn) {
                clearSearchPeroranganBtn.style.display = term !== '' ? 'flex' : 'none';
            }

            if (noResultsPerorangan) {
                noResultsPerorangan.style.display = (visibleCount === 0 && cards.length > 0) ? 'block' : 'none';
            }
        }

        if (searchPeroranganInput) {
            ['input', 'keyup', 'change', 'paste'].forEach(evt => {
                searchPeroranganInput.addEventListener(evt, function() {
                    filterPerorangan(this.value);
                });
            });
        }

        if (clearSearchPeroranganBtn) {
            clearSearchPeroranganBtn.addEventListener('click', function() {
                if (searchPeroranganInput) {
                    searchPeroranganInput.value = '';
                    searchPeroranganInput.focus();
                    filterPerorangan('');
                }
            });
        }

        // Select All Perorangan Button (respects search filter)
        const selectAllPeroranganBtn = document.getElementById('selectAllPeroranganBtn');
        if (selectAllPeroranganBtn) {
            let allSelected = false;
            selectAllPeroranganBtn.addEventListener('click', function() {
                allSelected = !allSelected;
                const visibleCards = Array.from(document.querySelectorAll('.student-item-card')).filter(card => card.style.display !== 'none');
                visibleCards.forEach(card => {
                    const cb = card.querySelector('.student-checkbox');
                    if (cb) cb.checked = allSelected;
                });
                selectAllPeroranganBtn.innerHTML = allSelected 
                    ? '<i class="fas fa-times"></i> <span>Batal Pilih</span>' 
                    : '<i class="fas fa-check-double"></i> <span>Pilih Semua</span>';
                updateSelectedCounter();
            });
        }

        // ----------------------------------------------------
        // ROBUST LIVE SEARCH (KELOMPOK)
        // ----------------------------------------------------
        const searchKelompokInput = document.getElementById('searchKelompokInput');
        const clearSearchKelompokBtn = document.getElementById('clearSearchKelompokBtn');
        const noResultsKelompok = document.getElementById('noResultsKelompok');

        function filterKelompok(term) {
            term = (term || '').toLowerCase().trim();
            const cards = document.querySelectorAll('.group-card-item');
            let visibleCount = 0;

            cards.forEach(card => {
                const searchData = ((card.getAttribute('data-search') || '') + ' ' + card.innerText).toLowerCase();
                const isMatch = term === '' || searchData.includes(term);
                if (isMatch) {
                    card.style.setProperty('display', 'block', 'important');
                    visibleCount++;
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            });

            if (clearSearchKelompokBtn) {
                clearSearchKelompokBtn.style.display = term !== '' ? 'flex' : 'none';
            }

            if (noResultsKelompok) {
                noResultsKelompok.style.display = (visibleCount === 0 && cards.length > 0) ? 'block' : 'none';
            }
        }

        if (searchKelompokInput) {
            ['input', 'keyup', 'change', 'paste'].forEach(evt => {
                searchKelompokInput.addEventListener(evt, function() {
                    filterKelompok(this.value);
                });
            });
        }

        if (clearSearchKelompokBtn) {
            clearSearchKelompokBtn.addEventListener('click', function() {
                if (searchKelompokInput) {
                    searchKelompokInput.value = '';
                    searchKelompokInput.focus();
                    filterKelompok('');
                }
            });
        }

        // Group Checkbox changed -> toggle its member checkboxes
        document.querySelectorAll('.group-checkbox').forEach(gcb => {
            gcb.addEventListener('change', function() {
                const groupId = this.getAttribute('data-group-id');
                const isChecked = this.checked;
                const memberCheckboxes = document.querySelectorAll('.group-member-checkbox-' + groupId + ':not(:disabled)');
                memberCheckboxes.forEach(mcb => mcb.checked = isChecked);
                updateSelectedCounter();
            });
        });

        // Individual Member Checkbox changed -> synchronize group checkbox
        document.querySelectorAll('.group-member-checkbox').forEach(mcb => {
            mcb.addEventListener('change', function() {
                const groupId = this.getAttribute('data-group');
                const allMembers = document.querySelectorAll('.group-member-checkbox-' + groupId + ':not(:disabled)');
                const checkedMembers = document.querySelectorAll('.group-member-checkbox-' + groupId + ':checked:not(:disabled)');
                const groupCb = document.querySelector('.group-checkbox[data-group-id="' + groupId + '"]');

                if (groupCb) {
                    if (allMembers.length > 0 && checkedMembers.length === allMembers.length) {
                        groupCb.checked = true;
                        groupCb.indeterminate = false;
                    } else if (checkedMembers.length > 0) {
                        groupCb.checked = false;
                        groupCb.indeterminate = true;
                    } else {
                        groupCb.checked = false;
                        groupCb.indeterminate = false;
                    }
                }
                updateSelectedCounter();
            });
        });

        // Select All Groups Button (respects search filter)
        const selectAllKelompokBtn = document.getElementById('selectAllKelompokBtn');
        if (selectAllKelompokBtn) {
            let allGroupsSelected = false;
            selectAllKelompokBtn.addEventListener('click', function() {
                allGroupsSelected = !allGroupsSelected;
                const visibleGroups = Array.from(document.querySelectorAll('.group-card-item')).filter(card => card.style.display !== 'none');
                visibleGroups.forEach(card => {
                    const gcb = card.querySelector('.group-checkbox:not(:disabled)');
                    if (gcb) {
                        gcb.checked = allGroupsSelected;
                        gcb.indeterminate = false;
                        const groupId = gcb.getAttribute('data-group-id');
                        const memberCheckboxes = card.querySelectorAll('.group-member-checkbox-' + groupId + ':not(:disabled)');
                        memberCheckboxes.forEach(mcb => mcb.checked = allGroupsSelected);
                    }
                });
                selectAllKelompokBtn.innerHTML = allGroupsSelected 
                    ? '<i class="fas fa-times"></i> <span>Batal Pilih Semua</span>' 
                    : '<i class="fas fa-check-double"></i> <span>Pilih Semua Kelompok</span>';
                updateSelectedCounter();
            });
        }

        // Accordion Toggle for Group Members
        document.querySelectorAll('.accordion-toggle').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const targetEl = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (targetEl) {
                    if (targetEl.classList.contains('hidden')) {
                        targetEl.classList.remove('hidden');
                        if (icon) icon.style.transform = 'rotate(180deg)';
                    } else {
                        targetEl.classList.add('hidden');
                        if (icon) icon.style.transform = 'rotate(0deg)';
                    }
                }
            });
        });

        // Listen for all student checkboxes change
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedCounter);
        });

        // Form Validation on Submit
        const form = document.getElementById('assignDosenPengujiForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const mode = "{{ $selectedType }}";
                let selectedCount = 0;

                if (mode === 'perorangan') {
                    selectedCount = document.querySelectorAll('.student-checkbox:checked').length;
                } else {
                    const groupSelected = document.querySelectorAll('.group-checkbox:checked').length;
                    const memberSelected = document.querySelectorAll('.group-member-checkbox:checked:not(:disabled)').length;
                    selectedCount = groupSelected + memberSelected;
                }

                if (selectedCount === 0) {
                    e.preventDefault();
                    alert('Silakan pilih minimal satu mahasiswa atau kelompok untuk di-plot ke dosen penguji.');
                    return false;
                }
            });
        }

        // Initial count
        updateSelectedCounter();
    });
</script>
@endsection
