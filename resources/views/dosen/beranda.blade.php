@extends('layouts.dosen')

@section('title', 'Beranda Dosen')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profil Dosen -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                </div>
                <div class="px-8 pb-8 text-center relative">
                    <div class="w-24 h-24 bg-white p-1 rounded-full mx-auto -mt-12 mb-4 shadow-lg">
                        <div class="w-full h-full bg-blue-50 rounded-full flex items-center justify-center text-3xl font-black text-blue-600">
                            {{ substr($dosen->nama, 0, 1) }}
                        </div>
                    </div>

                    <h4 class="text-xl font-black text-gray-800 tracking-tight mb-1">{{ $dosen->nama }}</h4>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">NIDN: {{ $dosen->nidn }}</p>

                    <div class="bg-gray-50 rounded-2xl p-6 text-left space-y-4 border border-gray-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm border border-gray-100">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tahun Akademik Aktif</p>
                                <p class="text-sm font-bold text-gray-700">{{ $activeTA ? $activeTA->tahun.' '.$activeTA->semester : 'Belum diatur' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat & Quick Actions -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Stat Cards Row 1 -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="text-3xl font-black text-gray-800">{{ $totalBimbingan }}</h5>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Mahasiswa Bimbingan</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                        <i class="fas fa-gavel text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="text-3xl font-black text-gray-800">{{ $totalUjian }}</h5>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Mahasiswa Ujian</p>
                    </div>
                </div>
            </div>

            <!-- Stat Cards Row 2 - Per Kegiatan -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mx-auto mb-3">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h5 class="text-2xl font-black text-gray-800">{{ $countKKN }}</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">KKN</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mx-auto mb-3">
                        <i class="fas fa-school"></i>
                    </div>
                    <h5 class="text-2xl font-black text-gray-800">{{ $countPPL }}</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">PPL</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 mx-auto mb-3">
                        <i class="fas fa-building"></i>
                    </div>
                    <h5 class="text-2xl font-black text-gray-800">{{ $countPKL }}</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">PKL</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mx-auto mb-3">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h5 class="text-2xl font-black text-gray-800">{{ $countMagang }}</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Magang</p>
                </div>
            </div>

            <!-- Progress Penilaian -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                    Progress Penilaian Bimbingan
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Sudah Dinilai</p>
                        <h5 class="text-3xl font-black text-emerald-700">{{ $sudahDinilai }}</h5>
                    </div>
                    <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100 text-center">
                        <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-2">Belum Dinilai</p>
                        <h5 class="text-3xl font-black text-amber-700">{{ $belumDinilai }}</h5>
                    </div>
                </div>
                @if($totalBimbingan > 0)
                <div class="mt-4">
                    <div class="flex justify-between text-xs font-bold text-gray-400 mb-2">
                        <span>Progress</span>
                        <span>{{ $totalBimbingan > 0 ? round(($sudahDinilai / $totalBimbingan) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-3 rounded-full transition-all" style="width: {{ $totalBimbingan > 0 ? round(($sudahDinilai / $totalBimbingan) * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <a href="{{ route('dosen.bimbingan') }}" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 hover:border-blue-200 hover:shadow-md transition-all group">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors flex-shrink-0">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors">Mahasiswa Bimbingan</h4>
                    <p class="text-sm text-gray-500">Lihat daftar, filter, dan kelola nilai mahasiswa bimbingan Anda.</p>
                </div>
                <i class="fas fa-arrow-right text-gray-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all ml-auto"></i>
            </div>
        </a>
        <a href="{{ route('dosen.ujian.index') }}" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 hover:border-indigo-200 hover:shadow-md transition-all group">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors flex-shrink-0">
                    <i class="fas fa-gavel text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">Mahasiswa Ujian</h4>
                    <p class="text-sm text-gray-500">Lihat daftar dan input nilai ujian mahasiswa yang Anda uji.</p>
                </div>
                <i class="fas fa-arrow-right text-gray-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all ml-auto"></i>
            </div>
        </a>
    </div>
</div>
@endsection
