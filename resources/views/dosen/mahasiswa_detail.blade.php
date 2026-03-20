@extends('layouts.dosen')

@section('title', 'Detail Mahasiswa Bimbingan')

@section('content')
<div class="space-y-6">
    <!-- Header & Back Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dosen.dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Mahasiswa</h2>
                <p class="text-sm text-gray-500 font-medium">Pantau aktivitas dan berikan penilaian bimbingan</p>
            </div>
        </div>
    </div>

    <!-- Top Profile Header (Horizontal) -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-6">
        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl font-black shadow-lg shadow-blue-100 shrink-0">
            {{ substr($mahasiswa->nama, 0, 1) }}
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="text-xl font-black text-gray-800">{{ $mahasiswa->nama }}</h3>
            <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-2">
                <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $mahasiswa->nim }}</span>
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $mahasiswa->kegiatan }}</span>
                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black rounded-lg uppercase tracking-wider italic">{{ $mahasiswa->prodi_full }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-2 shrink-0 w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block text-center md:text-left">Lokasi Penempatan</span>
            <div class="flex items-center justify-center md:justify-start text-gray-700 font-bold text-sm">
                <i class="fas fa-map-marker-alt text-red-400 mr-2"></i>
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
        <!-- Left Side: Journal Timeline (Wider) -->
        <div class="lg:col-span-7 xl:col-span-8 order-2 lg:order-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <h4 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-history text-blue-500 mr-3"></i>
                        Jurnal Aktivitas Harian
                    </h4>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black rounded-full">{{ $jurnals->count() }} Entri</span>
                </div>

                <div class="p-8 flex-1">
                    @if($jurnals->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-calendar-times text-3xl opacity-20"></i>
                            </div>
                            <p class="italic font-medium">Mahasiswa belum mengisi jurnal harian.</p>
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

        <!-- Right Side: Action Panel -->
        <div class="lg:col-span-5 xl:col-span-4 order-1 lg:order-2 space-y-6">
            <!-- Form Nilai -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 sticky top-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-star text-amber-400 mr-2"></i>
                        Penilaian
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

                @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-xs font-bold flex items-center">
                    <i class="fas fa-check-circle mr-2 text-base"></i>{{ session('success') }}
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

                <!-- Luaran Publikasi Inside Sidebar (More Compact) -->
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

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
