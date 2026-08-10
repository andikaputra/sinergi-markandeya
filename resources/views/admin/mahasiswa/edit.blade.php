@extends('layouts.admin')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <button onclick="history.back()" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Data Mahasiswa</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white shadow-lg shadow-blue-100">
                    <i class="fas fa-user-edit text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Formulir Update Data</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Ubah data profil dan kegiatan aktif mahasiswa.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST" class="p-10 space-y-6">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Terjadi kesalahan:</strong>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM (Nomor Induk Mahasiswa)</label>
                <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required
                    class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required
                    class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Program Studi</label>
                <select name="prodi" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach(['PGSD' => 'S1 Pendidikan Guru Sekolah Dasar', 'PBSI' => 'S1 Pendidikan Bahasa dan Sastra Indonesia', 'PBI' => 'S1 Pendidikan Bahasa Inggris', 'SI' => 'S1 Sistem Informasi', 'ME' => 'S1 Manajemen Ekonomi', 'PARBUD' => 'S1 Pariwisata Budaya Dan Keagamaan', 'HUKUM' => 'S1 Hukum Adat'] as $code => $name)
                        <option value="{{ $code }}" {{ old('prodi', $mahasiswa->prodi) === $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Plotting Kegiatan</label>
                    <select name="kegiatan" class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        <option value="">-- Kosong / Pilih Kegiatan --</option>
                        <option value="KKN" {{ old('kegiatan', $mahasiswa->kegiatan) === 'KKN' ? 'selected' : '' }}>KKN (Kuliah Kerja Nyata)</option>
                        <option value="PPL" {{ old('kegiatan', $mahasiswa->kegiatan) === 'PPL' ? 'selected' : '' }}>PPL (Praktik Pengalaman Lapangan)</option>
                        <option value="PKL" {{ old('kegiatan', $mahasiswa->kegiatan) === 'PKL' ? 'selected' : '' }}>PKL (Praktik Kerja Lapangan)</option>
                        <option value="Magang" {{ old('kegiatan', $mahasiswa->kegiatan) === 'Magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Akademik</label>
                    <select name="tahun_akademik" class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        <option value="">-- Pilih Tahun Akademik --</option>
                        @foreach($tahunAkademiks as $ta)
                            <option value="{{ $ta->tahun }} {{ $ta->semester }}" {{ old('tahun_akademik', $mahasiswa->tahun_akademik) === ($ta->tahun . ' ' . $ta->semester) ? 'selected' : '' }}>
                                {{ $ta->tahun }} - {{ $ta->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-6 flex space-x-4">
                <button type="submit" class="flex-1 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-2 group transform hover:-translate-y-1">
                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Simpan Perubahan</span>
                </button>
                <button type="button" onclick="history.back()" class="px-10 py-5 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all uppercase tracking-widest text-xs">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
