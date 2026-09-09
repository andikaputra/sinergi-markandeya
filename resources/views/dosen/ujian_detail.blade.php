@extends('layouts.dosen')

@section('title', 'Detail Mahasiswa Ujian - ' . $mahasiswa->nama)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'jurnal' }">
    <!-- Header & Back Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dosen.ujian.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Penilaian Laporan Akhir & Ujian</h2>
                <p class="text-sm text-gray-500 font-medium">Evaluasi hasil akhir, berikan catatan revisi ujian, dan input nilai penguji</p>
            </div>
        </div>
    </div>

    <!-- Top Profile Header -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-6">
        <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-indigo-700 text-white rounded-2xl flex items-center justify-center text-2xl font-black shadow-lg shadow-indigo-100 shrink-0">
            {{ substr($mahasiswa->nama, 0, 1) }}
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="text-xl font-black text-gray-800">{{ $mahasiswa->nama }}</h3>
            <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-2">
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black rounded-lg uppercase tracking-wider font-mono">{{ $mahasiswa->nim }}</span>
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $mahasiswa->kegiatan }}</span>
                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $mahasiswa->prodi_full ?? $mahasiswa->prodi }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-2 shrink-0 w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 text-center md:text-left">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Lokasi Penempatan</span>
            <div class="flex items-center justify-center md:justify-start text-gray-700 font-bold text-sm">
                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                @if($mahasiswa->kegiatan == 'KKN')
                    Desa {{ $mahasiswa->penempatankkn?->lokasikkn?->desa ?? '-' }}
                @elseif($mahasiswa->kegiatan == 'PPL')
                    {{ $mahasiswa->penempatanppl?->lokasippl?->Sekolah ?? ($mahasiswa->penempatanppl?->lokasippl?->sekolah ?? '-') }}
                @elseif($mahasiswa->kegiatan == 'PKL')
                    {{ $mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                @elseif($mahasiswa->kegiatan == 'Magang')
                    {{ $mahasiswa->penempatanmagang?->lokasimagang?->nama_instansi ?? '-' }}        
                @else
                    -
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 flex items-center space-x-3 shadow-sm">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
            <i class="fas fa-check-circle text-lg"></i>
        </div>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Side: Tabs for Journal & Bimbingan History -->
        <div class="lg:col-span-7 xl:col-span-8 order-2 lg:order-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <!-- Tab Headers -->
                <div class="flex items-center border-b border-gray-100 bg-gray-50/50 p-2 gap-2">
                    <button
                        @click="activeTab = 'jurnal'"
                        :class="activeTab === 'jurnal' ? 'bg-white text-indigo-600 shadow-sm font-black' : 'text-gray-500 hover:text-gray-700 font-bold'"
                        class="flex-1 py-3 px-4 rounded-2xl text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transition-all"
                    >
                        <i class="fas fa-history"></i>
                        <span>Jurnal Aktivitas ({{ $jurnals->count() }})</span>
                    </button>
                    <button
                        @click="activeTab = 'bimbingan'"
                        :class="activeTab === 'bimbingan' ? 'bg-white text-indigo-600 shadow-sm font-black' : 'text-gray-500 hover:text-gray-700 font-bold'"
                        class="flex-1 py-3 px-4 rounded-2xl text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transition-all"
                    >
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Log Bimbingan Mahasiswa ({{ $bimbingans->count() }})</span>
                    </button>
                </div>

                <!-- TAB 1: JURNAL AKTIVITAS -->
                <div x-show="activeTab === 'jurnal'" class="p-6 md:p-8 flex-1">
                    @if($jurnals->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <i class="fas fa-calendar-times text-3xl opacity-20 mb-4"></i>
                            <p class="italic font-medium">Mahasiswa belum mengisi jurnal harian.</p>
                        </div>
                    @else
                        <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gray-100">
                            @foreach ($jurnals as $jurnal)
                            <div class="relative flex items-start group" x-data="{ expanded: false }">
                                <div class="absolute left-0 w-10 h-10 bg-white border-2 border-indigo-500 rounded-xl flex items-center justify-center text-indigo-600 z-10 transition-all group-hover:bg-indigo-600 group-hover:text-white shadow-sm">
                                    <span class="text-[10px] font-black">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d') }}</span>
                                </div>
                                <div class="flex-1 ml-16 bg-white p-5 rounded-2xl border border-gray-100 group-hover:border-indigo-100 group-hover:shadow-md transition-all duration-300">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">
                                            {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('F Y') }}
                                        </span>
                                        <span class="text-[9px] text-gray-400 font-bold uppercase"><i class="far fa-clock mr-1"></i> {{ $jurnal->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="relative">
                                        <p class="text-gray-700 leading-relaxed text-sm transition-all" :class="expanded ? '' : 'line-clamp-2'">
                                            {{ $jurnal->kegiatan }}
                                        </p>
                                        @if(strlen($jurnal->kegiatan) > 150)
                                        <button @click="expanded = !expanded" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest mt-2 focus:outline-none flex items-center gap-1">
                                            <span x-text="expanded ? 'Sembunyikan' : 'Baca Selengkapnya'"></span>
                                            <i class="fas transition-transform" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- TAB 2: LOG BIMBINGAN (READ-ONLY FOR PENGUJI) -->
                <div x-show="activeTab === 'bimbingan'" class="p-6 md:p-8 flex-1 space-y-6" style="display: none;">
                    <div class="pb-4 border-b border-gray-100">
                        <h4 class="text-base font-black text-gray-800">Riwayat Konsultasi & Log Bimbingan Mahasiswa</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Dosen Penguji dapat meninjau proses konsultasi dan catatan yang telah diberikan oleh Dosen Pembimbing</p>
                    </div>

                    @if($bimbingans->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <div class="w-16 h-16 bg-indigo-50 text-indigo-400 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-comment-slash text-2xl"></i>
                            </div>
                            <p class="font-bold text-gray-600">Belum Ada Sesi Bimbingan</p>
                            <p class="text-xs text-gray-400 mt-1">Mahasiswa belum memiliki catatan log bimbingan dengan pembimbing.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($bimbingans as $b)
                            <div class="bg-gray-50/70 border border-gray-100 rounded-2xl p-5 space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-gray-200/60">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-xs">
                                            #{{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-bold text-gray-900">{{ $b->topik }}</h5>
                                            <p class="text-[10px] text-gray-500 font-semibold">
                                                {{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->translatedFormat('d M Y, H:i') }} WITA
                                            </p>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase self-start sm:self-auto
                                        @if($b->status === 'disetujui') bg-emerald-100 text-emerald-800
                                        @elseif($b->status === 'perlu_revisi') bg-amber-100 text-amber-800
                                        @else bg-blue-100 text-blue-800
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', $b->status) }}
                                    </span>
                                </div>

                                <div class="text-xs text-gray-700 leading-relaxed bg-white p-3 rounded-xl border border-gray-100">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Uraian Bimbingan:</span>
                                    {{ $b->deskripsi }}
                                </div>

                                @if($b->materi_terlampir)
                                <div class="flex items-center justify-between p-2.5 bg-indigo-50/50 border border-indigo-100 rounded-xl text-xs">
                                    <span class="font-medium text-gray-700 truncate max-w-xs"><i class="fas fa-paperclip text-indigo-600 mr-1.5"></i>{{ $b->materi_terlampir }}</span>
                                    <a href="{{ asset('storage/bimbingan/' . $b->materi_terlampir) }}" download class="text-indigo-600 hover:text-indigo-800 font-bold ml-2 shrink-0">
                                        <i class="fas fa-download mr-1"></i> Unduh
                                    </a>
                                </div>
                                @endif

                                @if($b->catatan_dosen)
                                <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl text-xs text-amber-950">
                                    <strong class="text-amber-800 block mb-0.5"><i class="fas fa-comment-dots mr-1"></i> Catatan Dosen Pembimbing:</strong>
                                    <p class="italic text-gray-800">{{ $b->catatan_dosen }}</p>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Panel Ujian & Catatan Revisi -->
        <div class="lg:col-span-5 xl:col-span-4 order-1 lg:order-2 space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 sticky top-6 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h4 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-gavel text-indigo-600 mr-2"></i>
                        Panel Penilaian & Revisi Ujian
                    </h4>
                    @if($isUjian->nilai !== null)
                        <div class="px-3 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full uppercase">Selesai</div>
                    @endif
                </div>

                @if($isUjian->nilai !== null)
                <div class="p-4 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl text-center shadow-lg shadow-indigo-100">
                    <span class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest opacity-80">
                        Skor Akhir Ujian
                    </span>
                    <p class="text-4xl font-black text-white mt-1">{{ $isUjian->nilai }}</p>
                </div>
                @endif

                <!-- Existing Note Preview if Available -->
                @if($isUjian->catatan)
                <div class="p-4 bg-amber-50/80 border border-amber-200 rounded-2xl text-xs text-amber-950 space-y-1">
                    <strong class="text-amber-900 flex items-center gap-1.5 font-black text-xs">
                        <i class="fas fa-comment-alt text-amber-600"></i> Catatan/Revisi Ujian Sebelumnya:
                    </strong>
                    <p class="text-gray-800 leading-relaxed italic whitespace-pre-wrap">{{ $isUjian->catatan }}</p>
                </div>
                @endif

                <form action="{{ route('dosen.ujian.nilai', $mahasiswa->nim) }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Nilai Input Section -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Skor Penilaian Ujian
                        </label>
                        @if($mahasiswa->kegiatan === 'PPL')
                            <label class="block p-4 bg-gray-50 rounded-2xl border border-gray-100 focus-within:border-indigo-500 transition-all text-center">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Nilai Ujian Laporan</span>
                                <input type="number" name="nilai" value="{{ old('nilai', $isUjian->nilai) }}" min="0" max="100" step="0.1"
                                    class="w-full bg-transparent border-none p-0 focus:ring-0 font-black text-3xl text-gray-800 text-center" placeholder="0">
                            </label>
                        @else
                            <div class="grid grid-cols-2 gap-2">
                                @php
                                    $kriteria_ujian = [
                                        ['key' => 'nilai_keterlaksanaan', 'label' => 'Prog (Pelaksanaan)'],
                                        ['key' => 'nilai_kontribusi', 'label' => 'Kont (Kontribusi)'],
                                        ['key' => 'nilai_kerjasama', 'label' => 'Tim (Kerjasama)'],
                                        ['key' => 'nilai_kreativitas', 'label' => 'Kreat (Kreativitas)'],
                                        ['key' => 'nilai_partisipasi', 'label' => 'Etika (Partisipasi)'],
                                    ];
                                @endphp
                                @foreach($kriteria_ujian as $k)
                                <label class="block p-3 bg-gray-50 rounded-xl border border-gray-100 focus-within:border-indigo-500 transition-all @if($loop->last) col-span-2 @endif">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 block">{{ $k['label'] }}</span>
                                    <input type="number" name="{{ $k['key'] }}" value="{{ old($k['key'], $isUjian->{$k['key']}) }}" min="0" max="100" step="0.1"
                                        class="w-full bg-transparent border-none p-0 focus:ring-0 font-black text-lg text-gray-800" placeholder="0">
                                </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Catatan / Arahan Revisi Ujian Section -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                            <span>Catatan & Arahan Revisi Ujian</span>
                            <span class="text-[10px] font-normal text-gray-400">(Opsional)</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-2">Tuliskan poin revisi laporan akhir, koreksi sistematika, atau arahan perbaikan untuk mahasiswa setelah ujian.</p>
                        <textarea
                            name="catatan"
                            rows="4"
                            placeholder="Contoh: Perbaiki bab pembahasan mengenai hasil program kerja, lengkapi lampiran dokumentasi foto, dan sesuaikan format penulisan daftar pustaka..."
                            class="w-full p-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs font-medium text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all resize-y leading-relaxed"
                        >{{ old('catatan', $isUjian->catatan) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-black text-xs rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-[0.98] uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Nilai & Catatan Ujian</span>
                    </button>
                </form>

                <!-- Luaran / File Laporan -->
                <div class="pt-5 border-t border-gray-100">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center">
                        <i class="fas fa-file-pdf mr-2 text-rose-500"></i> Berkas Laporan Akhir
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($mahasiswa->publikasis as $pub)
                            <a href="{{ $pub->link }}" target="_blank" class="px-3.5 py-2 bg-rose-50 text-rose-700 rounded-xl text-xs font-bold hover:bg-rose-600 hover:text-white transition-all flex items-center gap-1.5 shadow-sm">
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                                <span>{{ Str::limit($pub->judul ?? 'Link Laporan', 20) }}</span>
                            </a>
                        @empty
                            <p class="text-xs text-gray-400 italic">Belum tersedia</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
