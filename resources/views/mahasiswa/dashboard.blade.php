@extends('layouts.adminmhs')

@section('title', 'Beranda Mahasiswa')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profil Mahasiswa -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden relative group">
                <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                </div>
                <div class="px-8 pb-8 text-center relative">
                    <div class="w-24 h-24 bg-white p-1 rounded-full mx-auto -mt-12 mb-4 shadow-lg">
                        <div class="w-full h-full bg-blue-50 rounded-full flex items-center justify-center text-3xl font-black text-blue-600">
                            {{ substr(Auth::user()->name ?? 'M', 0, 1) }}
                        </div>
                    </div>
                    
                    <h4 class="text-xl font-black text-gray-800 tracking-tight mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">{{ Auth::user()->nim ?? '-' }}</p>
                    
                    <div class="bg-gray-50 rounded-2xl p-6 text-left space-y-4 border border-gray-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm border border-gray-100">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kampus</p>
                                <p class="text-sm font-bold text-gray-700">{{ Auth::user()->kampus ?? 'Markandeya' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm border border-gray-100">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Program Studi</p>
                                <p class="text-sm font-bold text-gray-700 line-clamp-1">{{ Auth::user()->prodi_full ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Kegiatan -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 h-full flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-xl font-bold text-gray-800">Status Kegiatan</h4>
                    <span class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-black border border-emerald-100 uppercase tracking-wide">
                        {{ Auth::user()->kegiatan }}
                    </span>
                </div>

                <div class="flex-1 bg-gray-50 rounded-[2rem] border border-gray-100 p-8 relative overflow-hidden group">
                    <!-- Background Pattern -->
                    <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="fas fa-map-marked-alt text-9xl transform translate-x-10 -translate-y-10"></i>
                    </div>

                    <div class="relative z-10 h-full flex flex-col justify-center">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Lokasi Penempatan</p>
                        
                        @if(Auth::user()->kegiatan == 'KKN')
                            @if (isset($penempatankknmhs) && $penempatankknmhs)
                                <h2 class="text-3xl font-black text-gray-800 mb-2">Desa {{ $penempatankknmhs->lokasikkn->desa }}</h2>
                                <div class="flex items-center text-gray-500 font-medium">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                    <span>Kabupaten {{ $penempatankknmhs->lokasikkn->kabupaten }}</span>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Belum Ditentukan</h3>
                                    <p class="text-gray-500 text-sm mt-1">Mohon menunggu proses penempatan oleh admin.</p>
                                </div>
                            @endif
                        @elseif (Auth::user()->kegiatan == 'PPL')
                            @if (isset($penempatanpplmhs) && $penempatanpplmhs)
                                <h2 class="text-3xl font-black text-gray-800 mb-2">{{ $penempatanpplmhs->lokasippl->Sekolah }}</h2>
                                <div class="flex items-center text-gray-500 font-medium">
                                    <i class="fas fa-school mr-2 text-emerald-500"></i>
                                    <span>Mitra Sekolah Resmi</span>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Belum Ditentukan</h3>
                                    <p class="text-gray-500 text-sm mt-1">Mohon menunggu proses penempatan oleh admin.</p>
                                </div>
                            @endif
                        @elseif (Auth::user()->kegiatan == 'PKL')
                            @if (isset($penempatanpklmhs) && $penempatanpklmhs)
                                <h2 class="text-3xl font-black text-gray-800 mb-2">{{ $penempatanpklmhs->lokasipkl->nama_instansi }}</h2>
                                <div class="flex items-center text-gray-500 font-medium">
                                    <i class="fas fa-building mr-2 text-amber-500"></i>
                                    <span>Instansi / Perusahaan PKL</span>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Belum Ditentukan</h3>
                                    <p class="text-gray-500 text-sm mt-1">Mohon menunggu proses penempatan oleh admin.</p>
                                </div>
                            @endif
                        @elseif (Auth::user()->kegiatan == 'Magang')
                            @if (isset($penempatanmagangmhs) || (isset($mahasiswa->penempatanmagang) && $mahasiswa->penempatanmagang))
                                @php $pm = $penempatanmagangmhs ?? $mahasiswa->penempatanmagang; @endphp
                                <h2 class="text-3xl font-black text-gray-800 mb-2">{{ $pm->lokasimagang->nama_instansi }}</h2>
                                <div class="flex items-center text-gray-500 font-medium">
                                    <i class="fas fa-briefcase mr-2 text-indigo-500"></i>
                                    <span>Lokasi Magang Resmi</span>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Belum Ditentukan</h3>
                                    <p class="text-gray-500 text-sm mt-1">Mohon menunggu proses penempatan oleh admin.</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 italic">Informasi detail lokasi akan muncul setelah proses administrasi selesai.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <a href="{{ route('jurnal.create') }}" class="flex items-center justify-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all group">
                        <i class="fas fa-pen-nib mr-3 group-hover:-translate-y-1 transition-transform"></i>
                        Isi Jurnal
                    </a>
                    <a href="{{ route('publikasi.index') }}" class="flex items-center justify-center px-6 py-4 bg-white border border-gray-200 hover:border-blue-200 hover:text-blue-600 text-gray-600 font-bold rounded-2xl transition-all">
                        <i class="fas fa-file-alt mr-3"></i>
                        Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Extra Features: Laporan Akhir & Nilai Akhir -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Unggah Link Laporan -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
            <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-cloud-upload-alt text-blue-500 mr-3"></i>
                Laporan Akhir (Link)
            </h4>
            <form action="{{ route('mahasiswa.save_laporan') }}" method="POST" class="space-y-4">
                @csrf
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-link text-sm"></i>
                    </div>
                    <input type="url" name="laporan_link" value="{{ $mahasiswa->laporan_link }}" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-medium text-sm"
                        placeholder="Link Google Drive / Dropbox PDF Laporan">
                </div>
                <button type="submit" class="w-full py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-black transition-all shadow-lg shadow-slate-200">
                    Simpan Link Laporan
                </button>
            </form>
            @if($mahasiswa->laporan_link)
                <div class="mt-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center">
                    <i class="fas fa-check-circle text-emerald-500 mr-2"></i>
                    <span class="text-xs text-emerald-700 font-medium truncate">Tersimpan: <a href="{{ $mahasiswa->laporan_link }}" target="_blank" class="underline">{{ $mahasiswa->laporan_link }}</a></span>
                </div>
            @endif
        </div>

        <!-- Rekap Nilai Akhir -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
            <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-graduation-cap text-indigo-500 mr-3"></i>
                Rekap Nilai Akhir
            </h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-6 bg-slate-50 rounded-2xl border border-gray-100 text-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nilai Pembimbing</p>
                    <h5 class="text-2xl font-black text-gray-800">{{ $mahasiswa->dosenPembimbing?->nilai ?? '-' }}</h5>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl border border-gray-100 text-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nilai Penguji</p>
                    <h5 class="text-2xl font-black text-gray-800">{{ $mahasiswa->dosenPenguji?->nilai ?? '-' }}</h5>
                </div>
            </div>
            <div class="mt-4 p-6 bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl text-white text-center">
                <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest mb-1 opacity-80">Nilai Akhir Sistem</p>
                <h2 class="text-4xl font-black">{{ $mahasiswa->nilai_akhir }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
