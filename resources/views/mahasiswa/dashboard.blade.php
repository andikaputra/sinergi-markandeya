@extends('layouts.adminmhs')

@section('title', 'Beranda Mahasiswa')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profil Mahasiswa -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative group">
                <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                </div>
                <div class="px-8 pb-8 text-center relative">
                    <div class="w-24 h-24 bg-white p-1 rounded-full mx-auto -mt-12 mb-4 shadow-lg">
                        <div class="w-full h-full bg-blue-50 rounded-full flex items-center justify-center text-3xl font-black text-blue-600">
                            {{ substr(Auth::user()->nama ?? 'M', 0, 1) }}
                        </div>
                    </div>

                    <h4 class="text-xl font-black text-gray-800 tracking-tight mb-1">{{ Auth::user()->nama }}</h4>
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
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-full flex flex-col">
                @if($hasActiveKegiatan)
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-xl font-bold text-gray-800">Status Kegiatan</h4>
                    <span class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-black border border-emerald-100 uppercase tracking-wide">
                        {{ Auth::user()->kegiatan }}
                    </span>
                </div>

                <div class="flex-1 bg-gray-50 rounded-[2rem] border border-gray-100 p-8 relative overflow-hidden group">
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
                                @include('mahasiswa._belum_ditentukan')
                            @endif
                        @elseif (Auth::user()->kegiatan == 'PPL')
                            @if (isset($penempatanpplmhs) && $penempatanpplmhs)
                                <h2 class="text-3xl font-black text-gray-800 mb-2">{{ $penempatanpplmhs->lokasippl->Sekolah }}</h2>
                                <div class="flex items-center text-gray-500 font-medium">
                                    <i class="fas fa-school mr-2 text-emerald-500"></i>
                                    <span>Mitra Sekolah Resmi</span>
                                </div>
                            @else
                                @include('mahasiswa._belum_ditentukan')
                            @endif
                        @elseif (Auth::user()->kegiatan == 'PKL')
                            @if (isset($penempatanpklmhs) && $penempatanpklmhs)
                                <h2 class="text-3xl font-black text-gray-800 mb-2">{{ $penempatanpklmhs->lokasipkl->nama_instansi }}</h2>
                                <div class="flex items-center text-gray-500 font-medium">
                                    <i class="fas fa-building mr-2 text-amber-500"></i>
                                    <span>Instansi / Perusahaan PKL</span>
                                </div>
                            @else
                                @include('mahasiswa._belum_ditentukan')
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
                                @include('mahasiswa._belum_ditentukan')
                            @endif
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
                        <i class="fas fa-newspaper mr-3"></i>
                        Publikasi
                    </a>
                </div>

                @else
                <!-- Belum Punya Kegiatan - Form Daftar Kegiatan -->
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-xl font-bold text-gray-800">Pilih Kegiatan</h4>
                    <span class="px-4 py-2 bg-amber-50 text-amber-700 rounded-xl text-xs font-black border border-amber-100 uppercase tracking-wide">
                        Belum Terdaftar
                    </span>
                </div>

                <div class="flex-1 bg-amber-50/50 rounded-[2rem] border border-amber-100 p-8">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clipboard-list text-2xl text-amber-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Daftar Kegiatan</h3>
                        <p class="text-sm text-gray-500">Pilih kegiatan yang ingin Anda ikuti di tahun akademik ini{{ $taString ? ' ('.$taString.')' : '' }}.</p>
                    </div>

                    <form action="{{ route('mahasiswa.daftar-kegiatan') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="kegiatan" value="KKN" class="peer sr-only" required>
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-2xl text-center peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                                    <i class="fas fa-hands-helping text-2xl text-blue-500 mb-2"></i>
                                    <p class="text-sm font-bold text-gray-700">KKN</p>
                                    <p class="text-[10px] text-gray-400">Kuliah Kerja Nyata</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="kegiatan" value="PPL" class="peer sr-only">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-2xl text-center peer-checked:border-emerald-600 peer-checked:bg-emerald-50 transition-all">
                                    <i class="fas fa-chalkboard-teacher text-2xl text-emerald-500 mb-2"></i>
                                    <p class="text-sm font-bold text-gray-700">PPL</p>
                                    <p class="text-[10px] text-gray-400">Praktik Pengalaman Lapangan</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="kegiatan" value="PKL" class="peer sr-only">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-2xl text-center peer-checked:border-amber-600 peer-checked:bg-amber-50 transition-all">
                                    <i class="fas fa-building text-2xl text-amber-500 mb-2"></i>
                                    <p class="text-sm font-bold text-gray-700">PKL</p>
                                    <p class="text-[10px] text-gray-400">Praktik Kerja Lapangan</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="kegiatan" value="Magang" class="peer sr-only">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-2xl text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all">
                                    <i class="fas fa-briefcase text-2xl text-indigo-500 mb-2"></i>
                                    <p class="text-sm font-bold text-gray-700">Magang</p>
                                    <p class="text-[10px] text-gray-400">Internship</p>
                                </div>
                            </label>
                        </div>
                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all">
                            <i class="fas fa-check-circle mr-2"></i> Daftar Kegiatan
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Riwayat Kegiatan (jika punya lebih dari 1) -->
    @if($riwayatKegiatan->count() > 1)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-history text-blue-500 mr-3"></i>
            Riwayat Kegiatan
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($riwayatKegiatan as $rk)
            <div class="p-4 rounded-2xl border {{ $rk->is_active ? 'border-blue-200 bg-blue-50' : 'border-gray-100 bg-gray-50' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold {{ $rk->is_active ? 'text-blue-700' : 'text-gray-700' }}">{{ $rk->kegiatan }}</span>
                    @if($rk->is_active)
                        <span class="px-2 py-0.5 bg-blue-600 text-white text-[10px] font-bold rounded-full uppercase">Aktif</span>
                    @else
                        <form action="{{ route('mahasiswa.switch-kegiatan') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="kegiatan_id" value="{{ $rk->id }}">
                            <button type="submit" class="px-2 py-0.5 bg-gray-200 hover:bg-blue-600 hover:text-white text-gray-600 text-[10px] font-bold rounded-full uppercase transition-all">
                                Aktifkan
                            </button>
                        </form>
                    @endif
                </div>
                <p class="text-xs text-gray-500">{{ $rk->tahun_akademik ?? '-' }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Extra Features: Laporan Akhir & Nilai Akhir -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Unggah Link Laporan -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
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
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-200 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-medium text-sm"
                        placeholder="Link Google Drive / Dropbox PDF Laporan">
                </div>
                @error('laporan_link')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all">
                    <i class="fas fa-save mr-2"></i> Simpan Link Laporan
                </button>
            </form>
            @if($mahasiswa->laporan_link)
                <div class="mt-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center">
                    <i class="fas fa-check-circle text-emerald-500 mr-2"></i>
                    <span class="text-xs text-emerald-700 font-medium truncate">Tersimpan: <a href="{{ $mahasiswa->laporan_link }}" target="_blank" rel="noopener noreferrer" class="underline">{{ $mahasiswa->laporan_link }}</a></span>
                </div>
            @endif
        </div>

        <!-- Rekap Nilai Akhir -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-graduation-cap text-indigo-500 mr-3"></i>
                Rekap Nilai Akhir
            </h4>
            <div class="grid grid-cols-{{ $mahasiswa->pembimbingLuarMahasiswa ? '3' : '2' }} gap-4">
                <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100 text-center">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Nilai Pembimbing</p>
                    <h5 class="text-3xl font-black text-blue-700">{{ $mahasiswa->dosenPembimbing?->nilai ?? '-' }}</h5>
                </div>
                <div class="p-6 bg-indigo-50 rounded-2xl border border-indigo-100 text-center">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Nilai Penguji</p>
                    <h5 class="text-3xl font-black text-indigo-700">{{ $mahasiswa->dosenPenguji?->nilai ?? '-' }}</h5>
                </div>
                @if($mahasiswa->pembimbingLuarMahasiswa)
                <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Nilai Pemb. Luar</p>
                    <h5 class="text-3xl font-black text-emerald-700">{{ $mahasiswa->pembimbingLuarMahasiswa?->nilai ?? '-' }}</h5>
                </div>
                @endif
            </div>
            <div class="mt-4 p-6 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl text-white text-center shadow-lg shadow-blue-200">
                <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest mb-1 opacity-80">Nilai Akhir</p>
                <h2 class="text-5xl font-black">{{ $mahasiswa->nilai_akhir }}</h2>
            </div>
        </div>
    </div>

    <!-- Dosen Pembimbing, Penguji & Pembimbing Luar -->
    @if($mahasiswa->dosenPembimbing || $mahasiswa->dosenPenguji || $mahasiswa->pembimbingLuarMahasiswa)
    <div class="grid grid-cols-1 lg:grid-cols-{{ $mahasiswa->pembimbingLuarMahasiswa ? '3' : '2' }} gap-8">
        @if($mahasiswa->dosenPembimbing?->dosen)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-chalkboard-teacher text-blue-500 mr-3"></i>
                Dosen Pembimbing
            </h4>
            <div class="flex items-center space-x-4 p-5 bg-blue-50 rounded-2xl border border-blue-100">
                <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl font-black flex-shrink-0 shadow-lg shadow-blue-200">
                    {{ substr($mahasiswa->dosenPembimbing->dosen->nama, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-base font-bold text-gray-800 truncate">{{ $mahasiswa->dosenPembimbing->dosen->nama }}</p>
                    <p class="text-xs text-blue-600 font-mono font-bold">NIDN: {{ $mahasiswa->dosenPembimbing->dosen->nidn }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($mahasiswa->dosenPenguji?->dosen)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-gavel text-indigo-500 mr-3"></i>
                Dosen Penguji
            </h4>
            <div class="flex items-center space-x-4 p-5 bg-indigo-50 rounded-2xl border border-indigo-100">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl font-black flex-shrink-0 shadow-lg shadow-indigo-200">
                    {{ substr($mahasiswa->dosenPenguji->dosen->nama, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-base font-bold text-gray-800 truncate">{{ $mahasiswa->dosenPenguji->dosen->nama }}</p>
                    <p class="text-xs text-indigo-600 font-mono font-bold">NIDN: {{ $mahasiswa->dosenPenguji->dosen->nidn }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($mahasiswa->pembimbingLuarMahasiswa?->pembimbingLuar)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-user-friends text-emerald-500 mr-3"></i>
                Pembimbing Luar
            </h4>
            <div class="flex items-center space-x-4 p-5 bg-emerald-50 rounded-2xl border border-emerald-100">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl font-black flex-shrink-0 shadow-lg shadow-emerald-200">
                    {{ substr($mahasiswa->pembimbingLuarMahasiswa->pembimbingLuar->nama, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-base font-bold text-gray-800 truncate">{{ $mahasiswa->pembimbingLuarMahasiswa->pembimbingLuar->nama }}</p>
                    <p class="text-xs text-emerald-600 font-bold">{{ $mahasiswa->pembimbingLuarMahasiswa->pembimbingLuar->instansi }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Teman Se-Lokasi -->
    @if($temanSeLokasi->isNotEmpty())
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-users text-blue-500 mr-3"></i>
                Teman Se-Lokasi
            </h4>
            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold border border-blue-100">
                {{ $temanSeLokasi->count() }} orang
            </span>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($temanSeLokasi as $teman)
            <div class="flex items-center space-x-4 px-8 py-4 hover:bg-blue-50/30 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                    {{ substr($teman->nama, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-800 truncate">{{ $teman->nama }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $teman->nim }}</p>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter bg-gray-100 px-2 py-0.5 rounded flex-shrink-0">
                    {{ $teman->prodi }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
