@extends('layouts.dosen')

@section('title', 'Detail Mahasiswa Bimbingan - ' . $mahasiswa->nama)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'bimbingan' }">
    <!-- Header & Back Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dosen.bimbingan') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Mahasiswa Bimbingan</h2>
                <p class="text-sm text-gray-500 font-medium">Evaluasi log bimbingan, berikan catatan arahan, dan input penilaian akhir</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 flex items-center space-x-3 shadow-sm">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
            <i class="fas fa-check-circle text-lg"></i>
        </div>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Top Profile Header (Horizontal) -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-6">
        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl font-black shadow-lg shadow-blue-100 shrink-0">
            {{ substr($mahasiswa->nama, 0, 1) }}
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="text-xl font-black text-gray-800">{{ $mahasiswa->nama }}</h3>
            <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-2">
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black rounded-lg uppercase tracking-wider font-mono">{{ $mahasiswa->nim }}</span>
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $mahasiswa->kegiatan }}</span>
                <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $mahasiswa->prodi_full }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-1 shrink-0 w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block text-center md:text-left">Lokasi Penempatan</span>
            <div class="flex items-center justify-center md:justify-start text-gray-800 font-bold text-sm">
                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                @if($mahasiswa->kegiatan == 'KKN')
                    Desa {{ $mahasiswa->penempatankkn?->lokasikkn?->desa ?? '-' }}
                @elseif($mahasiswa->kegiatan == 'PPL')
                    {{ $mahasiswa->penempatanppl?->lokasippl?->Sekolah ?? '-' }}
                @elseif($mahasiswa->kegiatan == 'PKL')
                    {{ $mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                @elseif($mahasiswa->kegiatan == 'Magang')
                    {{ $mahasiswa->penempatanmagang?->lokasimagang?->nama_instansi ?? '-' }}        
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Side: Tabs for Bimbingan & Jurnal (8 cols) -->
        <div class="lg:col-span-7 xl:col-span-8 order-2 lg:order-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Tab Headers -->
                <div class="flex items-center border-b border-gray-100 bg-gray-50/50 p-2 gap-2">
                    <button
                        @click="activeTab = 'bimbingan'"
                        :class="activeTab === 'bimbingan' ? 'bg-white text-blue-600 shadow-sm font-black' : 'text-gray-500 hover:text-gray-700 font-bold'"
                        class="flex-1 py-3 px-4 rounded-2xl text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transition-all"
                    >
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Log Bimbingan ({{ $bimbingans->count() }})</span>
                    </button>
                    <button
                        @click="activeTab = 'jurnal'"
                        :class="activeTab === 'jurnal' ? 'bg-white text-blue-600 shadow-sm font-black' : 'text-gray-500 hover:text-gray-700 font-bold'"
                        class="flex-1 py-3 px-4 rounded-2xl text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transition-all"
                    >
                        <i class="fas fa-history"></i>
                        <span>Jurnal Harian ({{ $jurnals->count() }})</span>
                    </button>
                </div>

                <!-- TAB 1: BIMBINGAN LIST & REVIEW FORM -->
                <div x-show="activeTab === 'bimbingan'" class="p-6 md:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <h4 class="text-base font-black text-gray-800">Sesi & Permohonan Bimbingan</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Tinjau draft laporan, ubah status persetujuan, dan berikan catatan arahan</p>
                        </div>
                    </div>

                    @if($bimbingans->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                            <div class="w-16 h-16 bg-blue-50 text-blue-400 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-comment-slash text-2xl"></i>
                            </div>
                            <p class="font-bold text-gray-600">Belum Ada Sesi Bimbingan</p>
                            <p class="text-xs text-gray-400 mt-1">Mahasiswa belum mengajukan konsultasi / bimbingan.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach ($bimbingans as $b)
                            <div class="bg-gray-50/70 border border-gray-100 rounded-3xl p-6 hover:border-blue-200 transition-all space-y-4" x-data="{ openReview: {{ $b->status === 'belum_direview' ? 'true' : 'false' }} }">
                                <!-- Top Bar -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-gray-200/60">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-xs">
                                            #{{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-black text-gray-900">{{ $b->topik }}</h5>
                                            <p class="text-[11px] text-gray-500 font-semibold">
                                                <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                                {{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->translatedFormat('d M Y, H:i') }} WITA
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="flex items-center space-x-2">
                                        @if($b->status === 'disetujui')
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-[11px] font-black rounded-full flex items-center">
                                                <i class="fas fa-check-circle mr-1"></i> Disetujui
                                            </span>
                                        @elseif($b->status === 'perlu_revisi')
                                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-[11px] font-black rounded-full flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Perlu Revisi
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-[11px] font-black rounded-full flex items-center">
                                                <i class="fas fa-clock mr-1"></i> Menunggu Review
                                            </span>
                                        @endif

                                        <button
                                            @click="openReview = !openReview"
                                            class="px-3 py-1 bg-white border border-gray-200 hover:border-blue-500 text-blue-600 text-xs font-bold rounded-xl transition-all shadow-xs"
                                        >
                                            <span x-text="openReview ? 'Tutup Form' : 'Beri Catatan / Review'"></span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Deskripsi Pokok Bahasan -->
                                <div class="bg-white p-4 rounded-2xl border border-gray-100 text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider block mb-1">Materi / Pertanyaan Mahasiswa:</span>
                                    {{ $b->deskripsi }}
                                </div>

                                <!-- Materi Terlampir -->
                                @if($b->materi_terlampir)
                                <div class="flex items-center justify-between p-3 bg-blue-50/50 border border-blue-100 rounded-2xl">
                                    <div class="flex items-center space-x-2.5">
                                        <i class="fas fa-paperclip text-blue-600"></i>
                                        <span class="text-xs font-bold text-gray-700">{{ $b->materi_terlampir }}</span>
                                    </div>
                                    <a
                                        href="{{ asset('storage/bimbingan/' . $b->materi_terlampir) }}"
                                        download
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center"
                                    >
                                        <i class="fas fa-download mr-1.5"></i> Unduh Draft
                                    </a>
                                </div>
                                @endif

                                <!-- Existing Catatan Dosen (Preview) -->
                                @if($b->catatan_dosen && !$b->isDirty())
                                <div class="p-3.5 bg-amber-50/80 border border-amber-200 rounded-2xl text-xs text-amber-950">
                                    <strong class="text-amber-800 flex items-center mb-1">
                                        <i class="fas fa-comment-dots mr-1"></i> Catatan Anda Sebelumnya:
                                    </strong>
                                    <p class="italic text-gray-800">"{{ $b->catatan_dosen }}"</p>
                                </div>
                                @endif

                                <!-- Form Review Catatan Dosen (Accordion / Expandable) -->
                                <div x-show="openReview" x-transition class="pt-3 border-t border-gray-200/60 bg-white p-5 rounded-2xl border border-blue-100 shadow-sm space-y-4">
                                    <h6 class="text-xs font-black text-gray-800 flex items-center">
                                        <i class="fas fa-pen-nib text-blue-600 mr-1.5"></i>
                                        Form Review & Catatan Dosen Pembimbing
                                    </h6>

                                    <form action="{{ route('dosen.bimbingan.status', $b->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        
                                        <!-- Status Pilihan -->
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-2">Status Persetujuan:</label>
                                            <div class="grid grid-cols-3 gap-3">
                                                <label class="flex items-center justify-center p-3 border rounded-xl cursor-pointer text-xs font-bold transition-all" :class="'{{ $b->status }}' === 'disetujui' ? 'bg-emerald-50 border-emerald-500 text-emerald-800' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                                                    <input type="radio" name="status" value="disetujui" class="mr-2 text-emerald-600 focus:ring-emerald-500" {{ $b->status === 'disetujui' ? 'checked' : '' }}>
                                                    <span>Disetujui (Acc)</span>
                                                </label>
                                                <label class="flex items-center justify-center p-3 border rounded-xl cursor-pointer text-xs font-bold transition-all" :class="'{{ $b->status }}' === 'perlu_revisi' ? 'bg-amber-50 border-amber-500 text-amber-800' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                                                    <input type="radio" name="status" value="perlu_revisi" class="mr-2 text-amber-600 focus:ring-amber-500" {{ $b->status === 'perlu_revisi' ? 'checked' : '' }}>
                                                    <span>Perlu Revisi</span>
                                                </label>
                                                <label class="flex items-center justify-center p-3 border rounded-xl cursor-pointer text-xs font-bold transition-all" :class="'{{ $b->status }}' === 'belum_direview' ? 'bg-blue-50 border-blue-500 text-blue-800' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                                                    <input type="radio" name="status" value="belum_direview" class="mr-2 text-blue-600 focus:ring-blue-500" {{ $b->status === 'belum_direview' ? 'checked' : '' }}>
                                                    <span>Menunggu</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Catatan Textarea -->
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Catatan & Masukan untuk Mahasiswa:</label>
                                            <textarea
                                                name="catatan_dosen"
                                                rows="3"
                                                placeholder="Tuliskan arahan revisi, koreksi sistematika laporan, atau instruksi selanjutnya untuk mahasiswa..."
                                                class="w-full p-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all resize-y"
                                            >{{ old('catatan_dosen', $b->catatan_dosen) }}</textarea>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="flex justify-end">
                                            <button
                                                type="submit"
                                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition-all flex items-center space-x-1.5"
                                            >
                                                <i class="fas fa-save"></i>
                                                <span>Simpan Catatan & Status</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- TAB 2: JURNAL AKTIVITAS HARIAN -->
                <div x-show="activeTab === 'jurnal'" class="p-6 md:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <h4 class="text-base font-black text-gray-800 flex items-center">
                            <i class="fas fa-history text-blue-500 mr-2"></i>
                            Jurnal Aktivitas Harian Mahasiswa
                        </h4>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black rounded-full">{{ $jurnals->count() }} Entri</span>
                    </div>

                    @if($jurnals->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-calendar-times text-2xl opacity-20"></i>
                            </div>
                            <p class="font-bold text-gray-500">Mahasiswa belum mengisi jurnal harian.</p>
                        </div>
                    @else
                        <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gray-100">
                            @foreach ($jurnals as $jurnal)
                            <div class="relative flex items-start group" x-data="{ expanded: false }">
                                <div class="absolute left-0 w-10 h-10 bg-white border-2 border-blue-500 rounded-xl flex items-center justify-center text-blue-600 z-10 transition-all group-hover:bg-blue-600 group-hover:text-white shadow-sm">
                                    <span class="text-[10px] font-black">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d') }}</span>
                                </div>
                                <div class="flex-1 ml-16 bg-white p-5 rounded-2xl border border-gray-100 group-hover:border-blue-100 group-hover:shadow-md transition-all duration-300">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">
                                            {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('F Y') }}
                                        </span>
                                        <span class="text-[9px] text-gray-400 font-bold uppercase"><i class="far fa-clock mr-1"></i> {{ $jurnal->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="relative">
                                        <p class="text-gray-700 leading-relaxed text-sm transition-all" :class="expanded ? '' : 'line-clamp-2'">
                                            {{ $jurnal->kegiatan }}
                                        </p>
                                        @if(strlen($jurnal->kegiatan) > 150)
                                        <button @click="expanded = !expanded" class="text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-widest mt-2 focus:outline-none flex items-center gap-1">
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
            </div>
        </div>

        <!-- Right Side: Penilaian Akhir & Link Luaran (4 cols) -->
        <div class="lg:col-span-5 xl:col-span-4 order-1 lg:order-2 space-y-6">
            <!-- Form Nilai -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 sticky top-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-star text-amber-400 mr-2"></i>
                        Penilaian Pembimbing
                    </h4>
                    @if($isBimbingan->nilai !== null)
                        <div class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black rounded-full uppercase">Update</div>
                    @endif
                </div>

                @if($isBimbingan->nilai !== null)
                <div class="mb-6 p-4 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl text-center shadow-lg shadow-blue-100">
                    <span class="text-[10px] font-bold text-blue-100 uppercase tracking-widest opacity-80">
                        Nilai Akhir
                    </span>
                    <p class="text-4xl font-black text-white mt-1">{{ $isBimbingan->nilai }}</p>
                </div>
                @endif

                <form action="{{ route('dosen.mahasiswa.nilai', $mahasiswa->nim) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @if(in_array($mahasiswa->kegiatan, ['PKL', 'Magang']))
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                    $kriteria_pkl = [
                                        ['key' => 'nilai_pkl_laporan', 'label' => 'Laporan (15%)'],
                                        ['key' => 'nilai_pkl_relevansi', 'label' => 'Relevansi (10%)'],
                                        ['key' => 'nilai_pkl_presentasi', 'label' => 'Ujian (15%)'],
                                    ];
                                @endphp
                                @foreach($kriteria_pkl as $k)
                                <label class="block p-3 bg-gray-50 rounded-xl border border-gray-100 focus-within:border-blue-500 transition-all @if($loop->last && count($kriteria_pkl) % 2 != 0) col-span-2 @endif">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 block">{{ $k['label'] }}</span>
                                    <input type="number" name="{{ $k['key'] }}" value="{{ $isBimbingan->{$k['key']} }}" min="0" max="100" step="0.1"
                                        class="w-full bg-transparent border-none p-0 focus:ring-0 font-black text-lg text-gray-800" placeholder="0">
                                </label>
                                @endforeach
                            </div>
                        @else
                            <label class="block p-4 bg-gray-50 rounded-2xl border border-gray-100 focus-within:border-blue-500 transition-all text-center">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Skor Pembimbing (0-100)</span>
                                <input type="number" name="nilai" value="{{ $isBimbingan->nilai }}" min="0" max="100" step="0.1"
                                    class="w-full bg-transparent border-none p-0 focus:ring-0 font-black text-3xl text-gray-800 text-center" placeholder="0">
                            </label>
                        @endif

                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm rounded-2xl shadow-xl shadow-blue-100 transition-all active:scale-[0.98] uppercase tracking-widest">
                            <i class="fas fa-save mr-2"></i> Update Nilai
                        </button>
                    </div>
                </form>

                <!-- Luaran Publikasi Inside Sidebar -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center">
                        <i class="fas fa-link mr-2"></i> Link Luaran
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($mahasiswa->publikasis as $pub)
                            <a href="{{ $pub->link }}" target="_blank" class="px-3 py-2 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-black hover:bg-blue-600 hover:text-white transition-all flex items-center">
                                <i class="fas fa-file-alt mr-2"></i> {{ Str::limit($pub->judul ?? 'Link Luaran', 15) }}
                            </a>
                        @empty
                            <p class="text-[10px] font-bold text-gray-400 italic">Belum ada link</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
