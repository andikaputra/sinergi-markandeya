@extends('layouts.dosen')

@section('title', 'Detail Mahasiswa Bimbingan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('dosen.dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-600 transition-all">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Detail & Jurnal Mahasiswa</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <div class="w-24 h-24 bg-blue-100 text-blue-600 rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-6 shadow-lg shadow-blue-50 border-4 border-white">
                    {{ substr($mahasiswa->nama, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-gray-800">{{ $mahasiswa->nama }}</h3>
                <p class="text-sm font-mono text-gray-400 mt-1 uppercase tracking-widest">{{ $mahasiswa->nim }}</p>
                
                <div class="mt-8 pt-8 border-t border-gray-50 space-y-4 text-left">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Program Studi</span>
                        <span class="text-sm font-semibold text-gray-700">{{ $mahasiswa->prodi_full }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jenis Kegiatan</span>
                        <span class="text-sm font-semibold text-gray-700">{{ $mahasiswa->kegiatan }}</span>
                    </div>
                </div>
            </div>

            <!-- Input Nilai -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-star text-amber-400 mr-2"></i>
                    Penilaian Mahasiswa
                </h4>
                <form action="{{ route('dosen.mahasiswa.nilai', $mahasiswa->nim) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Skor / Predikat</span>
                            <input type="text" name="nilai" value="{{ $isBimbingan->nilai }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-bold text-lg" placeholder="Contoh: A, 90, dll">
                        </label>
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-100 transition-all">
                            Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Journal Timeline -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[600px]">
                <div class="p-8 border-b border-gray-50">
                    <h4 class="text-lg font-bold text-gray-800">Aktivitas Jurnal Harian</h4>
                </div>
                
                <div class="p-8">
                    @if($jurnals->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <i class="fas fa-calendar-times text-5xl opacity-20 mb-4"></i>
                            <p class="italic">Mahasiswa belum mengisi jurnal harian.</p>
                        </div>
                    @else
                        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-blue-500 before:via-blue-200 before:to-transparent">
                            @foreach ($jurnals as $jurnal)
                            <div class="relative flex items-start group">
                                <div class="absolute left-0 w-10 h-10 bg-blue-50 border-4 border-white rounded-full flex items-center justify-center text-blue-600 z-10 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                                    <i class="far fa-clock text-xs"></i>
                                </div>
                                <div class="flex-1 ml-16 bg-gray-50/50 p-6 rounded-2xl border border-gray-100 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                    <span class="text-xs font-black text-blue-600 uppercase tracking-widest mb-2 block">
                                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}
                                    </span>
                                    <p class="text-gray-700 leading-relaxed">{{ $jurnal->kegiatan }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
