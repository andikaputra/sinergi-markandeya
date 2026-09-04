@extends('layouts.adminmhs')

@section('title', 'Bimbingan Dosen - ' . ($mahasiswa->kegiatan ?? 'Kegiatan'))

@section('content')
<div class="space-y-8">
    <!-- Header Hero Card -->
    <div class="p-8 rounded-3xl text-white shadow-xl border border-slate-700/50 relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e1b4b 100%);">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 bg-amber-400 text-slate-950 text-xs font-black rounded-lg uppercase tracking-wider shadow-sm">
                        {{ $mahasiswa->kegiatan ?? 'Kegiatan' }}
                    </span>
                    <span class="px-3 py-1 bg-white/10 text-white text-xs font-bold rounded-lg backdrop-blur-sm border border-white/20">
                        {{ $mahasiswa->tahun_akademik ?? 'Tahun Akademik Aktif' }}
                    </span>
                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 text-xs font-bold rounded-lg border border-emerald-500/30">
                        {{ $mahasiswa->prodi_full }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Bimbingan & Konsultasi {{ $mahasiswa->kegiatan }}
                </h1>
                <p class="text-slate-300 text-sm max-w-2xl leading-relaxed">
                    Catat riwayat konsultasi berkala, ajukan topik pembahasan dan draft laporan, serta pantau catatan dan persetujuan dari Dosen Pembimbing Anda.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('bimbingan.cetak') }}" target="_blank" class="inline-flex items-center space-x-2 px-5 py-3.5 bg-white/10 hover:bg-white/20 text-white font-bold text-sm rounded-2xl border border-white/25 backdrop-blur-sm transition-all duration-200 active:scale-95 shadow-sm">
                    <i class="fas fa-print text-amber-400"></i>
                    <span>Cetak Kartu Bimbingan</span>
                </a>
                <a href="{{ route('bimbingan.create') }}" class="inline-flex items-center space-x-2 px-6 py-3.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-black text-sm rounded-2xl transition-all duration-200 shadow-lg shadow-amber-400/20 active:scale-95">
                    <i class="fas fa-plus"></i>
                    <span>Ajukan Bimbingan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Feedback Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 flex items-center space-x-3 shadow-sm animate-fade-in">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
            <i class="fas fa-check-circle text-lg"></i>
        </div>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 flex items-center space-x-3 shadow-sm">
        <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
            <i class="fas fa-exclamation-circle text-lg"></i>
        </div>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    <!-- 2 Column: Info Pembimbing & Lokasi + Statistik Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Info Card: Dosen & Penempatan -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Informasi Bimbingan</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                <!-- Dosen Info -->
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-xl font-bold shrink-0 border border-primary-100">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Dosen Pembimbing</p>
                        <h4 class="text-base font-black text-gray-800">{{ $dosenPembimbing->dosen->nama ?? 'Belum Di-plotting' }}</h4>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">NIDN: {{ $dosenPembimbing->dosen->nidn ?? '-' }}</p>
                    </div>
                </div>

                <!-- Lokasi Penempatan Info -->
                <div class="flex items-start space-x-4 pt-2 border-t border-gray-50">
                    <div class="w-12 h-12 rounded-2xl bg-gold-50 text-gold-600 flex items-center justify-center text-xl font-bold shrink-0 border border-gold-100">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Penempatan {{ $mahasiswa->kegiatan }}</p>
                        <p class="text-sm font-bold text-gray-800">
                            @if($mahasiswa->kegiatan == 'KKN')
                                Desa {{ $mahasiswa->penempatankkn?->lokasikkn?->desa ?? '-' }}, Kec. {{ $mahasiswa->penempatankkn?->lokasikkn?->kecamatan ?? '-' }}
                            @elseif($mahasiswa->kegiatan == 'PPL')
                                {{ $mahasiswa->penempatanppl?->lokasippl?->Sekolah ?? '-' }}
                            @elseif($mahasiswa->kegiatan == 'PKL')
                                {{ $mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                            @elseif($mahasiswa->kegiatan == 'Magang')
                                {{ $mahasiswa->penempatanmagang?->lokasimagang?->nama_instansi ?? '-' }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>Total log bimbingan: <strong class="text-gray-800">{{ $statistik['total'] }} sesi</strong></span>
                <span class="text-emerald-600 font-bold"><i class="fas fa-shield-alt mr-1"></i> Terverifikasi Sistem</span>
            </div>
        </div>

        <!-- 4 Metric Cards -->
        <div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <!-- Total Bimbingan -->
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-base font-bold mb-3">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Sesi</span>
                    <span class="text-3xl font-black text-gray-800">{{ $statistik['total'] }}</span>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="bg-white p-5 rounded-3xl border border-emerald-100 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold mb-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider block mb-1">Disetujui</span>
                    <span class="text-3xl font-black text-emerald-700">{{ $statistik['disetujui'] }}</span>
                </div>
            </div>

            <!-- Perlu Revisi -->
            <div class="bg-white p-5 rounded-3xl border border-amber-100 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-base font-bold mb-3">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider block mb-1">Perlu Revisi</span>
                    <span class="text-3xl font-black text-amber-700">{{ $statistik['perlu_revisi'] }}</span>
                </div>
            </div>

            <!-- Belum Direview -->
            <div class="bg-white p-5 rounded-3xl border border-blue-100 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-base font-bold mb-3">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-blue-600 uppercase tracking-wider block mb-1">Menunggu</span>
                    <span class="text-3xl font-black text-blue-700">{{ $statistik['belum_direview'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Bimbingan Table Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-black text-gray-800">Riwayat Permohonan & Catatan Bimbingan</h3>
                <p class="text-xs text-gray-400 mt-1">Daftar semua sesi bimbingan yang telah diajukan kepada dosen pembimbing</p>
            </div>
            <span class="px-4 py-1.5 bg-gray-100 text-gray-700 rounded-full text-xs font-bold self-start sm:self-center">
                {{ $bimbingans->total() }} Permohonan
            </span>
        </div>

        @if($bimbingans->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Topik & Pokok Bahasan</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Waktu Bimbingan</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Catatan Dosen</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($bimbingans as $index => $bimbingan)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-5 text-gray-400 font-bold">
                            {{ ($bimbingans->currentPage()-1) * $bimbingans->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="space-y-1 max-w-xs md:max-w-md">
                                <p class="font-bold text-gray-900 leading-snug">{{ $bimbingan->topik }}</p>
                                <p class="text-xs text-gray-500 line-clamp-1">{{ $bimbingan->deskripsi }}</p>
                                @if($bimbingan->materi_terlampir)
                                <div class="inline-flex items-center text-[11px] text-primary-600 font-bold mt-1">
                                    <i class="fas fa-paperclip mr-1"></i> Lampiran terunggah
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="text-gray-800 font-semibold text-xs">
                                <i class="far fa-calendar-alt text-gray-400 mr-1.5"></i>
                                {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-gray-400 text-[11px] mt-0.5">
                                <i class="far fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('H:i') }} WITA
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($bimbingan->catatan_dosen)
                                <div class="p-2.5 bg-amber-50/80 border border-amber-200/60 rounded-xl text-xs text-amber-900 max-w-xs">
                                    <p class="font-bold flex items-center text-amber-800 mb-0.5">
                                        <i class="fas fa-comment-dots mr-1"></i> Catatan Dosen:
                                    </p>
                                    <p class="line-clamp-2 text-gray-700 italic">"{{ $bimbingan->catatan_dosen }}"</p>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center whitespace-nowrap">
                            @if($bimbingan->status === 'disetujui')
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-black shadow-xs">
                                    <i class="fas fa-check-circle mr-1.5 text-[10px]"></i> Disetujui
                                </span>
                            @elseif($bimbingan->status === 'perlu_revisi')
                                <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-black shadow-xs">
                                    <i class="fas fa-exclamation-circle mr-1.5 text-[10px]"></i> Perlu Revisi
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-black shadow-xs">
                                    <i class="fas fa-hourglass-half mr-1.5 text-[10px]"></i> Menunggu Review
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right whitespace-nowrap space-x-2">
                            @if($bimbingan->status === 'perlu_revisi')
                            <a href="{{ route('bimbingan.edit', $bimbingan->id) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all shadow-xs active:scale-95">
                                <i class="fas fa-edit text-[10px]"></i>
                                <span>Revisi</span>
                            </a>
                            @endif
                            <a href="{{ route('bimbingan.show', $bimbingan->id) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:text-primary-600 hover:border-primary-600 hover:bg-primary-50 transition-all shadow-sm">
                                <span>Detail</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-gray-100 flex justify-center">
            {{ $bimbingans->links() }}
        </div>
        @else
        <div class="p-16 text-center">
            <div class="w-20 h-20 bg-primary-50 text-primary-500 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4 border border-primary-100">
                <i class="fas fa-comments"></i>
            </div>
            <h4 class="text-lg font-bold text-gray-800">Belum Ada Riwayat Bimbingan</h4>
            <p class="text-sm text-gray-500 max-w-md mx-auto mt-1 mb-6">
                Anda belum pernah mengajukan sesi bimbingan kepada dosen pembimbing. Silakan ajukan topik konsultasi pertama Anda!
            </p>
            <a href="{{ route('bimbingan.create') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-gold-500 hover:bg-gold-600 text-primary-950 font-black text-sm rounded-xl transition-all shadow-md shadow-gold-500/20">
                <i class="fas fa-plus"></i>
                <span>Ajukan Bimbingan Pertama</span>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
