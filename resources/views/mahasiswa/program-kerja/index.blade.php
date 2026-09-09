@extends('layouts.adminmhs')

@section('title', 'Program Kerja')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'individu' }">
    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ $kegiatan ? strtoupper($kegiatan) : 'KEGIATAN' }}
                </span>
                <span class="text-xs text-gray-400">• Mahasiswa Portal</span>
            </div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight mt-1">Program Kerja & Luaran</h2>
            <p class="text-sm text-gray-500">Kelola rencana kerja individu dan kelompok beserta target deliverable luaran kegiatan.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <template x-if="activeTab === 'individu'">
                <a href="{{ route('program-kerja.create-individu') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl transition-all shadow-md shadow-blue-200 group">
                    <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform"></i>
                    Buat Program Individu
                </a>
            </template>
            <template x-if="activeTab === 'kelompok'">
                @if ($isKetua)
                    <a href="{{ route('program-kerja.create-kelompok') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl transition-all shadow-md shadow-indigo-200 group">
                        <i class="fas fa-crown mr-2 text-amber-300 group-hover:scale-110 transition-transform"></i>
                        Buat Program Kelompok
                    </a>
                @elseif (in_array(strtolower($kegiatan ?? ''), ['kkn', 'ppl']))
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold rounded-2xl">
                        <i class="fas fa-crown text-amber-500"></i>
                        <span>Ketua: {{ $ketuaKelompok->nama ?? 'Belum Ditunjuk' }}</span>
                    </div>
                @else
                    <a href="{{ route('program-kerja.create-kelompok') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl transition-all shadow-md shadow-indigo-200 group">
                        <i class="fas fa-users mr-2 group-hover:scale-110 transition-transform"></i>
                        Buat Program Kelompok
                    </a>
                @endif
            </template>
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

    <!-- Tabs Navigation -->
    <div class="flex border-b border-gray-200 gap-4">
        <button @click="activeTab = 'individu'" 
            :class="activeTab === 'individu' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
            class="pb-3 px-4 border-b-2 text-sm transition-all flex items-center gap-2">
            <i class="fas fa-user text-sm"></i>
            <span>Program Individu</span>
            <span class="px-2 py-0.5 text-xs rounded-full" :class="activeTab === 'individu' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'">
                {{ $statistikIndividu['total'] }}
            </span>
        </button>
        <button @click="activeTab = 'kelompok'" 
            :class="activeTab === 'kelompok' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
            class="pb-3 px-4 border-b-2 text-sm transition-all flex items-center gap-2">
            <i class="fas fa-users text-sm"></i>
            <span>Program Kelompok</span>
            <span class="px-2 py-0.5 text-xs rounded-full" :class="activeTab === 'kelompok' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600'">
                {{ $statistikKelompok['total'] }}
            </span>
        </button>
    </div>

    <!-- TAB 1: INDIVIDU -->
    <div x-show="activeTab === 'individu'" class="space-y-6">
        @if ($dosenMonevIndividu && $dosenMonevIndividu->dosen)
            <div class="p-5 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl text-white shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-xl text-blue-300 flex-shrink-0">
                        <i class="fas fa-search-location"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-sm text-white">Dosen Pemonev: {{ $dosenMonevIndividu->dosen->nama }}</h4>
                            @if ($dosenMonevIndividu->catatan || $dosenMonevIndividu->link_monev || !empty($dosenMonevIndividu->foto_monev) || !is_null($dosenMonevIndividu->nilai))
                                <span class="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-bold rounded-full flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Sudah Dievaluasi
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-amber-500/20 text-amber-300 border border-amber-400/30 text-[10px] font-bold rounded-full flex items-center gap-1">
                                    <i class="fas fa-clock"></i> Menunggu Evaluasi
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-blue-200 mt-1">
                            @if ($dosenMonevIndividu->tanggal_monev)
                                Pelaksanaan Monev: <strong>{{ \Carbon\Carbon::parse($dosenMonevIndividu->tanggal_monev)->format('d M Y') }}</strong>
                            @else
                                Dosen ditugaskan untuk mengevaluasi program kerja mandiri Anda.
                            @endif
                            @if (!is_null($dosenMonevIndividu->nilai))
                                • Nilai Monev: <strong class="text-white bg-white/10 px-2 py-0.5 rounded">{{ $dosenMonevIndividu->nilai }}</strong>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if ($dosenMonevIndividu->link_monev)
                        <a href="{{ $dosenMonevIndividu->link_monev }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fab fa-google-drive"></i>
                            <span>Foto Google Drive</span>
                            <i class="fas fa-external-link-alt text-[10px]"></i>
                        </a>
                    @endif
                    @if ($individuPrograms->isNotEmpty())
                        <a href="{{ route('program-kerja.show-individu', $individuPrograms->first()) }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fas fa-eye"></i>
                            <span>Lihat Detail Evaluasi</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-800">{{ $statistikIndividu['total'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Total Program</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-800">{{ $statistikIndividu['rencana'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Rencana</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-spinner"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-800">{{ $statistikIndividu['sedang_berjalan'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Sedang Berjalan</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-800">{{ $statistikIndividu['selesai'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Selesai</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @if ($individuPrograms->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Belum Ada Program Kerja Individu</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">Mulai tambahkan program kerja mandiri Anda untuk kegiatan {{ ucfirst($kegiatan) }}.</p>
                    <a href="{{ route('program-kerja.create-individu') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl mt-4 transition shadow-md shadow-blue-200">
                        <i class="fas fa-plus mr-2"></i> Tambah Program Sekarang
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="px-6 py-4">Judul Program</th>
                                <th class="px-6 py-4">Lokasi</th>
                                <th class="px-6 py-4">Jadwal Pelaksanaan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach ($individuPrograms as $program)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        <a href="{{ route('program-kerja.show-individu', $program) }}" class="hover:text-blue-600 transition-colors">
                                            {{ $program->judul }}
                                        </a>
                                        @if ($program->catatan_dosen)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md border border-blue-100">
                                                    <i class="fas fa-comment-dots text-[9px]"></i> Ada Catatan Dosen
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <i class="fas fa-map-marker-alt text-rose-500 text-xs mr-1"></i>
                                        {{ $program->lokasi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <div class="text-xs">
                                            <span class="font-medium text-gray-700">{{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}</span>
                                            s/d
                                            <span class="font-medium text-gray-700">{{ $program->tanggal_selesai ? $program->tanggal_selesai->format('d M Y') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($program->status === 'rencana')
                                            <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">Rencana</span>
                                        @elseif ($program->status === 'sedang_berjalan')
                                            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold rounded-full">Sedang Berjalan</span>
                                        @elseif ($program->status === 'selesai')
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">Selesai</span>
                                        @else
                                            <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">{{ ucfirst($program->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('program-kerja.show-individu', $program) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Detail & Luaran">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('program-kerja.edit-individu', $program) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition-colors" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('program-kerja.destroy-individu', $program) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus program kerja ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($individuPrograms->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $individuPrograms->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- TAB 2: KELOMPOK -->
    <div x-show="activeTab === 'kelompok'" class="space-y-6" style="display: none;">
        @if (in_array(strtolower($kegiatan ?? ''), ['kkn', 'ppl']))
            @if ($isKetua)
                <div class="p-4 sm:p-5 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 rounded-3xl text-white shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fas fa-crown text-amber-200"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-sm text-white flex items-center gap-2">
                                <span>Anda adalah Ketua Kelompok</span>
                                <span class="px-2 py-0.5 bg-white/20 text-white text-[10px] font-bold rounded-md uppercase">Resmi</span>
                            </h4>
                            <p class="text-xs text-amber-100 mt-0.5">Anda memiliki wewenang untuk membuat dan mengelola program kerja kelompok di lokasi penempatan ini.</p>
                        </div>
                    </div>
                    <a href="{{ route('program-kerja.create-kelompok') }}" class="px-4 py-2 bg-white hover:bg-amber-50 text-amber-900 text-xs font-black rounded-xl shadow-sm transition inline-flex items-center gap-1.5 self-start sm:self-auto whitespace-nowrap">
                        <i class="fas fa-plus text-[10px]"></i>
                        <span>Buat Program Kelompok</span>
                    </a>
                </div>
            @else
                <div class="p-4 sm:p-5 bg-slate-50 border border-slate-200 rounded-3xl flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-slate-200 text-slate-700 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="text-xs text-slate-700">
                        <p class="font-bold text-slate-900 text-sm">Peran Anda: Anggota Kelompok</p>
                        <p class="mt-0.5 text-slate-600 leading-relaxed">
                            Pengajuan Program Kerja Kelompok dilakukan oleh Ketua Kelompok: 
                            @if ($ketuaKelompok)
                                <strong class="text-slate-900 font-bold">{{ $ketuaKelompok->nama }}</strong> (NIM: {{ $ketuaKelompok->nim }}).
                            @else
                                <span class="italic text-amber-700 font-semibold">(Ketua kelompok belum ditetapkan oleh Admin).</span>
                            @endif
                            Seluruh anggota di lokasi yang sama akan otomatis melihat dan berkontribusi pada program kerja kelompok yang diajukan ketua.
                        </p>
                    </div>
                </div>
            @endif
        @endif

        @if ($dosenMonevKelompok && $dosenMonevKelompok->dosen)
            <div class="p-5 bg-gradient-to-r from-indigo-950 via-purple-950 to-slate-900 rounded-3xl text-white shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-xl text-purple-300 flex-shrink-0">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-sm text-white">Dosen Pemonev Kelompok: {{ $dosenMonevKelompok->dosen->nama }}</h4>
                            @if ($dosenMonevKelompok->catatan || $dosenMonevKelompok->link_monev || !empty($dosenMonevKelompok->foto_monev) || !is_null($dosenMonevKelompok->nilai))
                                <span class="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-bold rounded-full flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Sudah Dievaluasi
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-amber-500/20 text-amber-300 border border-amber-400/30 text-[10px] font-bold rounded-full flex items-center gap-1">
                                    <i class="fas fa-clock"></i> Menunggu Evaluasi
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-purple-200 mt-1">
                            @if ($dosenMonevKelompok->tanggal_monev)
                                Pelaksanaan Monev: <strong>{{ \Carbon\Carbon::parse($dosenMonevKelompok->tanggal_monev)->format('d M Y') }}</strong>
                            @else
                                Dosen ditugaskan untuk monitoring & evaluasi lapangan lokasi kelompok Anda.
                            @endif
                            @if (!is_null($dosenMonevKelompok->nilai))
                                • Nilai Monev: <strong class="text-white bg-white/10 px-2 py-0.5 rounded">{{ $dosenMonevKelompok->nilai }}</strong>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if ($dosenMonevKelompok->link_monev)
                        <a href="{{ $dosenMonevKelompok->link_monev }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fab fa-google-drive"></i>
                            <span>Foto Google Drive</span>
                            <i class="fas fa-external-link-alt text-[10px]"></i>
                        </a>
                    @endif
                    @if ($kelompokPrograms->isNotEmpty())
                        <a href="{{ route('program-kerja.show-kelompok', $kelompokPrograms->first()) }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fas fa-eye"></i>
                            <span>Lihat Detail Evaluasi</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-800">{{ $statistikKelompok['total'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Total Program</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-800">{{ $statistikKelompok['rencana'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Rencana</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-spinner"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-800">{{ $statistikKelompok['sedang_berjalan'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Sedang Berjalan</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-800">{{ $statistikKelompok['selesai'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Selesai</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @if ($kelompokPrograms->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Belum Ada Program Kerja Kelompok</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                        @if ($isKetua)
                            Mulai tambahkan rencana program kerja kelompok Anda untuk lokasi penempatan ini.
                        @else
                            Program kerja kelompok akan muncul di sini setelah diajukan oleh Ketua Kelompok 
                            ({{ $ketuaKelompok->nama ?? 'Ketua' }}).
                        @endif
                    </p>
                    @if ($isKetua || !in_array(strtolower($kegiatan ?? ''), ['kkn', 'ppl']))
                        <a href="{{ route('program-kerja.create-kelompok') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl mt-4 transition shadow-md shadow-indigo-200">
                            <i class="fas fa-plus mr-2"></i> Buat Program Kelompok
                        </a>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="px-6 py-4">Judul Program</th>
                                <th class="px-6 py-4">Ketua Pengusul</th>
                                <th class="px-6 py-4">Lokasi</th>
                                <th class="px-6 py-4">Jadwal</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach ($kelompokPrograms as $program)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        <a href="{{ route('program-kerja.show-kelompok', $program) }}" class="hover:text-indigo-600 transition-colors">
                                            {{ $program->judul }}
                                        </a>
                                        @if ($program->catatan_dosen)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded-md border border-purple-100">
                                                    <i class="fas fa-comment-dots text-[9px]"></i> Ada Catatan Dosen
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                                {{ substr($program->mahasiswaKetua->nama ?? 'K', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-xs">{{ $program->mahasiswaKetua->nama ?? '-' }}</div>
                                                <div class="text-[10px] text-gray-400">{{ $program->nim_ketua }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <i class="fas fa-map-marker-alt text-rose-500 text-xs mr-1"></i>
                                        {{ $program->lokasi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <div class="text-xs">
                                            <span class="font-medium text-gray-700">{{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}</span>
                                            s/d
                                            <span class="font-medium text-gray-700">{{ $program->tanggal_selesai ? $program->tanggal_selesai->format('d M Y') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($program->status === 'rencana')
                                            <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">Rencana</span>
                                        @elseif ($program->status === 'sedang_berjalan')
                                            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold rounded-full">Sedang Berjalan</span>
                                        @elseif ($program->status === 'selesai')
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">Selesai</span>
                                        @else
                                            <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">{{ ucfirst($program->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('program-kerja.show-kelompok', $program) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors" title="Detail & Anggota">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if (auth('mahasiswa')->user()->nim === $program->nim_ketua)
                                                <a href="{{ route('program-kerja.edit-kelompok', $program) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition-colors" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('program-kerja.destroy-kelompok', $program) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus program kerja kelompok ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($kelompokPrograms->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $kelompokPrograms->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
