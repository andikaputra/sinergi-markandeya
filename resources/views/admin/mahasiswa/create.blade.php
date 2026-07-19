@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <button onclick="history.back()" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Mahasiswa</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white shadow-lg shadow-blue-100">
                    <i class="fas fa-user-plus text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Formulir Pendaftaran</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Admin hanya perlu mengisi NIM, Nama, dan Program Studi. Data lainnya dilengkapi mahasiswa saat mendaftar kegiatan.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="p-10 space-y-8">
            @csrf

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
                <input type="text" name="nim" value="{{ old('nim') }}" required
                    class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                    placeholder="Contoh: 2021010001">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                    placeholder="Nama lengkap mahasiswa">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Program Studi</label>
                <select name="prodi" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                    <option value="">-- Pilih Program Studi --</option>
                    <option value="PGSD" {{ old('prodi') == 'PGSD' ? 'selected' : '' }}>S1 Pendidikan Guru Sekolah Dasar</option>
                    <option value="PBSI" {{ old('prodi') == 'PBSI' ? 'selected' : '' }}>S1 Pendidikan Bahasa dan Sastra Indonesia</option>
                    <option value="PBI" {{ old('prodi') == 'PBI' ? 'selected' : '' }}>S1 Pendidikan Bahasa Inggris</option>
                    <option value="SI" {{ old('prodi') == 'SI' ? 'selected' : '' }}>S1 Sistem Informasi</option>
                    <option value="ME" {{ old('prodi') == 'ME' ? 'selected' : '' }}>S1 Manajemen Ekonomi</option>
                    <option value="PARBUD" {{ old('prodi') == 'PARBUD' ? 'selected' : '' }}>S1 Pariwisata Budaya Dan Keagamaan</option>
                    <option value="HUKUM" {{ old('prodi') == 'HUKUM' ? 'selected' : '' }}>S1 Hukum Adat</option>
                </select>
            </div>

            <div class="bg-amber-50 p-6 rounded-3xl border border-amber-100 flex items-start space-x-3">
                <i class="fas fa-info-circle text-amber-500 mt-1 flex-shrink-0"></i>
                <div class="text-xs text-amber-700 leading-relaxed font-medium space-y-1">
                    <p><strong>Catatan untuk mahasiswa:</strong></p>
                    <ul class="list-disc list-inside space-y-1 mt-1">
                        <li>Password default adalah <strong>NIM</strong> mahasiswa.</li>
                        <li>Mahasiswa login menggunakan NIM, lalu melengkapi profil (email, kampus, dll).</li>
                        <li>Pendaftaran kegiatan (KKN/PPL/PKL/Magang) dilakukan oleh mahasiswa secara mandiri.</li>
                    </ul>
                </div>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-2 group transform hover:-translate-y-1">
                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Simpan Mahasiswa</span>
                </button>
                <button type="button" onclick="history.back()" class="px-10 py-5 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all uppercase tracking-widest text-xs">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
