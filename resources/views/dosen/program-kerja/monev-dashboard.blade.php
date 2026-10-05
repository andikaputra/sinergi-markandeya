@extends('layouts.main')

@section('title', 'Monitoring & Evaluasi Program Kerja')

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 sm:p-10 shadow-xl border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 text-xs font-semibold uppercase tracking-wider">
                    <i class="fas fa-search-location"></i> Panel Dosen Pemonev
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Monitoring & Evaluasi (Monev) Program Kerja</h1>
                <p class="text-slate-300 text-sm max-w-2xl">
                    Kelola dan lakukan evaluasi lapangan mahasiswa yang ditugaskan kepada Anda dalam <strong>3 Tahap Monev</strong> (Monev 1 Awal, Monev 2 Tengah/Progres, dan Monev 3 Akhir).
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dosen.dashboard') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl border border-white/20 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Dashboard
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

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Penugasan</p>
                <h3 class="text-3xl font-black text-gray-900 mt-2">{{ $totalTugas }}</h3>
                <p class="text-xs text-gray-500 mt-1">Mahasiswa & kelompok monev</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl font-bold border border-indigo-100">
                <i class="fas fa-tasks"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sudah Dimonev</p>
                <h3 class="text-3xl font-black text-emerald-600 mt-2">{{ $totalSelesai }}</h3>
                <p class="text-xs text-gray-500 mt-1">Minimal 1 tahap monev terisi</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl font-bold border border-emerald-100">
                <i class="fas fa-check-double"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Belum Dimonev</p>
                <h3 class="text-3xl font-black text-amber-600 mt-2">{{ $totalBelum }}</h3>
                <p class="text-xs text-gray-500 mt-1">Belum ada tahap monev terisi</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 text-2xl font-bold border border-amber-100">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
    </div>

    <!-- Main Content List -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-gray-900">Daftar Mahasiswa & Kelompok yang Anda Monev</h2>
                <p class="text-sm text-gray-500 mt-1">Dosen dapat mengajukan evaluasi hingga <strong>3x Monev</strong> per mahasiswa/kelompok (Tahap 1, Tahap 2, dan Tahap 3)</p>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            @if ($monevPrograms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($monevPrograms as $monev)
                        @php
                            $typeLabel = $monev->monev_type === 'individu' ? 'Individu' : 'Kelompok';
                            $typeBadgeClass = $monev->monev_type === 'individu' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200';
                            $kegiatan = strtoupper($monev->kegiatan ?? 'KKN');

                            if ($monev->monev_type === 'individu') {
                                $program = $monev->program_id ? App\Models\IndividuProgramKerja::find($monev->program_id) : null;
                                $mhs = $monev->mahasiswa ?: ($program?->mahasiswa ?: App\Models\Mahasiswa::where('nim', $monev->nim)->first());
                                $title = $program?->judul ?? ('Monev Individu ' . $kegiatan . ' - ' . ($mhs?->nama ?? $monev->nim));
                                $picName = $mhs?->nama ?? '-';
                                $picNim = $mhs?->nim ?? $monev->nim;
                                $lokasiName = match(strtolower($monev->kegiatan ?? 'kkn')) {
                                    'kkn' => $mhs?->penempatankkn?->lokasikkn?->desa,
                                    'ppl' => $mhs?->penempatanppl?->lokasippl?->Sekolah ?? $mhs?->penempatanppl?->lokasippl?->sekolah ?? $mhs?->penempatanppl?->lokasippl?->nama_sekolah,
                                    'pkl' => $mhs?->penempatanpkl?->lokasipkl?->nama_instansi,
                                    'magang' => $mhs?->penempatanmagang?->lokasimagang?->nama_instansi,
                                    default => null,
                                };
                            } else {
                                $program = $monev->program_id ? App\Models\KelompokProgramKerja::find($monev->program_id) : null;
                                $lok = $monev->lokasiKkn ?: ($monev->lokasiPpl ?: ($monev->lokasiPpl ?: $monev->lokasiMagang));
                                $picName = $program?->mahasiswaKetua?->nama ?? ($lok?->desa ?? $lok?->Sekolah ?? $lok?->sekolah ?? $lok?->nama_sekolah ?? $lok?->nama_instansi ?? 'Kelompok #' . $monev->lokasi_id);
                                $picNim = $program?->nim_ketua ?? '-';
                                $title = $program?->judul ?? ('Monev Kelompok ' . $kegiatan . ' - ' . $picName);
                                $lokasiName = ($lok?->kecamatan ? 'Kec. ' . $lok->kecamatan : '') . ($lok?->kabupaten ? ', ' . $lok->kabupaten : '');
                            }

                            $t1 = $monev->getTahap(1);
                            $t2 = $monev->getTahap(2);
                            $t3 = $monev->getTahap(3);
                            $selesaiCount = $monev->monev_selesai_count;
                            $avgScore = $monev->rata_rata_nilai;
                        @endphp

                        <div class="bg-white rounded-3xl border border-gray-200 hover:border-indigo-400 hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                            <div class="p-6">
                                <!-- Top Badges & Score -->
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $typeBadgeClass }}">
                                            <i class="fas {{ $monev->monev_type === 'individu' ? 'fa-user' : 'fa-users' }} mr-1"></i> {{ $typeLabel }}
                                        </span>
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 uppercase">
                                            {{ $kegiatan }}
                                        </span>
                                    </div>
                                    
                                    <!-- Progress Pill -->
                                    <div class="flex items-center gap-2">
                                        @if (!is_null($avgScore))
                                            <div class="flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full font-black text-xs" title="Rata-rata Nilai Monev">
                                                <i class="fas fa-star text-amber-500 text-[10px]"></i>
                                                <span>{{ $avgScore }}</span>
                                            </div>
                                        @endif
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $selesaiCount == 3 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($selesaiCount > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-gray-100 text-gray-500') }}">
                                            <i class="fas fa-check-circle mr-1 text-[10px]"></i> {{ $selesaiCount }}/3 Selesai
                                        </span>
                                    </div>
                                </div>

                                <!-- Title -->
                                <h3 class="font-bold text-gray-900 text-lg group-hover:text-indigo-600 transition-colors line-clamp-2 mb-3">
                                    {{ $title }}
                                </h3>

                                <!-- PIC & Location Details -->
                                <div class="space-y-1.5 text-xs text-gray-600 mb-4 bg-gray-50/90 p-3.5 rounded-2xl border border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <i class="fas {{ $monev->monev_type === 'individu' ? 'fa-user-graduate' : 'fa-crown text-amber-600' }} w-4 text-gray-400"></i>
                                        <span><strong>{{ $monev->monev_type === 'individu' ? 'Mahasiswa' : 'Kelompok/Ketua' }}:</strong> {{ $picName }} @if($picNim !== '-') ({{ $picNim }}) @endif</span>
                                    </div>
                                    @if($lokasiName)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt w-4 text-rose-500"></i>
                                        <span class="truncate">{{ $lokasiName }}</span>
                                    </div>
                                    @endif
                                </div>

                                <!-- 3-Stage Progress Timeline Cards -->
                                <div class="grid grid-cols-3 gap-2 pt-1 pb-2">
                                    <!-- Tahap 1 -->
                                    <div class="p-2.5 rounded-xl border text-center transition-all {{ $t1->exists && $t1->is_filled ? 'bg-emerald-50/80 border-emerald-200 text-emerald-900' : 'bg-gray-50 border-gray-200 text-gray-500' }}">
                                        <div class="flex items-center justify-center gap-1 mb-1">
                                            <i class="fas {{ $t1->exists && $t1->is_filled ? 'fa-check-circle text-emerald-600' : 'fa-circle-notch text-gray-300' }} text-xs"></i>
                                            <span class="font-extrabold text-[11px]">Monev 1</span>
                                        </div>
                                        <p class="text-[10px] font-medium truncate">
                                            @if($t1->exists && $t1->is_filled)
                                                {{ !is_null($t1->nilai) ? 'Nilai: ' . $t1->nilai : 'Selesai' }}
                                            @else
                                                Belum
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Tahap 2 -->
                                    <div class="p-2.5 rounded-xl border text-center transition-all {{ $t2->exists && $t2->is_filled ? 'bg-emerald-50/80 border-emerald-200 text-emerald-900' : 'bg-gray-50 border-gray-200 text-gray-500' }}">
                                        <div class="flex items-center justify-center gap-1 mb-1">
                                            <i class="fas {{ $t2->exists && $t2->is_filled ? 'fa-check-circle text-emerald-600' : 'fa-circle-notch text-gray-300' }} text-xs"></i>
                                            <span class="font-extrabold text-[11px]">Monev 2</span>
                                        </div>
                                        <p class="text-[10px] font-medium truncate">
                                            @if($t2->exists && $t2->is_filled)
                                                {{ !is_null($t2->nilai) ? 'Nilai: ' . $t2->nilai : 'Selesai' }}
                                            @else
                                                Belum
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Tahap 3 -->
                                    <div class="p-2.5 rounded-xl border text-center transition-all {{ $t3->exists && $t3->is_filled ? 'bg-emerald-50/80 border-emerald-200 text-emerald-900' : 'bg-gray-50 border-gray-200 text-gray-500' }}">
                                        <div class="flex items-center justify-center gap-1 mb-1">
                                            <i class="fas {{ $t3->exists && $t3->is_filled ? 'fa-check-circle text-emerald-600' : 'fa-circle-notch text-gray-300' }} text-xs"></i>
                                            <span class="font-extrabold text-[11px]">Monev 3</span>
                                        </div>
                                        <p class="text-[10px] font-medium truncate">
                                            @if($t3->exists && $t3->is_filled)
                                                {{ !is_null($t3->nilai) ? 'Nilai: ' . $t3->nilai : 'Selesai' }}
                                            @else
                                                Belum
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer Action -->
                            <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $monev->updated_at ? 'Diperbarui ' . $monev->updated_at->diffForHumans() : 'Belum diupdate' }}
                                </span>
                                <a href="{{ route('dosen.program-kerja.monev-detail-id', $monev->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                    <span>Input Monev (3 Tahap)</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($monevPrograms->hasPages())
                    <div class="mt-8">
                        {{ $monevPrograms->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                    <div class="w-20 h-20 mx-auto rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl mb-4 border border-indigo-100 shadow-sm">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Belum Ada Program yang Ditugaskan</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mt-2">
                        Admin belum menugaskan Anda sebagai Dosen Pemonev untuk mahasiswa/kelompok.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
