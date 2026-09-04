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
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Monitoring & Evaluasi Program Kerja</h1>
                <p class="text-slate-300 text-sm max-w-2xl">
                    Kelola dan lakukan evaluasi lapangan/program kerja mahasiswa yang ditugaskan kepada Anda. Unggah catatan evaluasi, dokumentasi foto kegiatan, serta nilai monev.
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
                <p class="text-xs text-gray-500 mt-1">Program kerja individu & kelompok</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl font-bold border border-indigo-100">
                <i class="fas fa-tasks"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sudah Dimonev</p>
                <h3 class="text-3xl font-black text-emerald-600 mt-2">{{ $totalSelesai }}</h3>
                <p class="text-xs text-gray-500 mt-1">Ada catatan / dokumentasi / nilai</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl font-bold border border-emerald-100">
                <i class="fas fa-check-double"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Belum Dimonev</p>
                <h3 class="text-3xl font-black text-amber-600 mt-2">{{ $totalBelum }}</h3>
                <p class="text-xs text-gray-500 mt-1">Menunggu evaluasi lapangan</p>
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
                <h2 class="text-xl font-black text-gray-900">Daftar Program Kerja yang Anda Monev</h2>
                <p class="text-sm text-gray-500 mt-1">Klik pada kartu untuk menginput catatan evaluasi dan mengunggah foto dokumentasi hasil monev</p>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            @if ($monevPrograms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($monevPrograms as $monev)
                        @php
                            if ($monev->monev_type === 'individu') {
                                $program = App\Models\IndividuProgramKerja::with('mahasiswa')->find($monev->program_id);
                                $picName = $program?->mahasiswa?->nama ?? '-';
                                $picNim = $program?->mahasiswa?->nim ?? '-';
                                $typeLabel = 'Individu';
                                $typeBadgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                            } else {
                                $program = App\Models\KelompokProgramKerja::with('mahasiswaKetua')->find($monev->program_id);
                                $picName = $program?->mahasiswaKetua?->nama ?? '-';
                                $picNim = $program?->mahasiswaKetua?->nim ?? '-';
                                $typeLabel = 'Kelompok';
                                $typeBadgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                            }
                            $hasPhotos = !empty($monev->foto_monev) && count($monev->foto_monev) > 0;
                            $hasNotes = !empty($monev->catatan);
                            $hasScore = !is_null($monev->nilai);
                        @endphp

                        @if ($program)
                        <div class="bg-white rounded-2xl border border-gray-200 hover:border-indigo-400 hover:shadow-lg transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                            <div class="p-6">
                                <!-- Top Badges -->
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $typeBadgeClass }}">
                                            <i class="fas {{ $monev->monev_type === 'individu' ? 'fa-user' : 'fa-users' }} mr-1"></i> {{ $typeLabel }}
                                        </span>
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 uppercase">
                                            {{ $program->kategori }}
                                        </span>
                                    </div>
                                    @if ($hasScore)
                                        <div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full font-black text-sm">
                                            <i class="fas fa-star text-amber-500 text-xs"></i>
                                            <span>{{ $monev->nilai }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Title -->
                                <h3 class="font-bold text-gray-900 text-lg group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2">
                                    {{ $program->judul }}
                                </h3>

                                <!-- PIC & Location -->
                                <div class="space-y-1.5 text-xs text-gray-600 mb-4 bg-gray-50/80 p-3.5 rounded-xl border border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <i class="fas {{ $monev->monev_type === 'individu' ? 'fa-user-graduate' : 'fa-crown text-amber-600' }} w-4 text-gray-400"></i>
                                        <span><strong>{{ $monev->monev_type === 'individu' ? 'Mahasiswa' : 'Ketua' }}:</strong> {{ $picName }} ({{ $picNim }})</span>
                                    </div>
                                    @if($program->lokasi)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt w-4 text-rose-500"></i>
                                        <span class="truncate">{{ $program->lokasi }}</span>
                                    </div>
                                    @endif
                                    @if($monev->tanggal_monev)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-check w-4 text-indigo-500"></i>
                                        <span>Tgl Monev: <strong>{{ \Carbon\Carbon::parse($monev->tanggal_monev)->format('d M Y') }}</strong></span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Status indicators -->
                                <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
                                    @if ($hasNotes)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fas fa-file-alt"></i> Catatan Ada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 text-gray-500">
                                            <i class="far fa-file"></i> Belum ada catatan
                                        </span>
                                    @endif

                                    @if ($hasPhotos)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <i class="fas fa-camera"></i> {{ count($monev->foto_monev) }} Foto Tersimpan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 text-gray-500">
                                            <i class="far fa-image"></i> Belum ada foto
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer Action -->
                            <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $monev->updated_at ? 'Diperbarui ' . $monev->updated_at->diffForHumans() : 'Belum diupdate' }}
                                </span>
                                <a href="{{ route('dosen.program-kerja.monev-detail', ['type' => $monev->monev_type, 'programId' => $monev->program_id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                    <span>Detail & Monev</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                        @endif
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
                        Admin belum menugaskan Anda sebagai Dosen Pemonev untuk program kerja mahasiswa. Silakan hubungi admin prodi/kegiatan jika diperlukan.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
