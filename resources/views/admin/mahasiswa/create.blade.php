@extends('layouts.admin')

@section('title', 'Registrasi Mahasiswa Manual')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <button onclick="history.back()" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Peserta Manual</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white shadow-lg shadow-blue-100">
                    <i class="fas fa-user-plus text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Formulir Peserta</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Admin dapat mendaftarkan mahasiswa secara langsung ke dalam sistem.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            
            <!-- Section 1: Identitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM (Nomor Induk)</label>
                    <input type="text" name="nim" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                </div>
            </div>

            <!-- Section 2: Akademik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Program Studi</label>
                    <select name="prodi" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        <option value="PGSD">S1 Pendidikan Guru Sekolah Dasar</option>
                        <option value="PBSI">S1 Pendidikan Bahasa dan Sastra Indonesia</option>
                        <option value="PBI">S1 Pendidikan Bahasa Inggris</option>
                        <option value="SI">S1 Sistem Informasi</option>
                        <option value="ME">S1 Manajemen Ekonomi</option>
                        <option value="PARBUD">S1 Pariwisata Budaya Dan Keagamaan</option>
                        <option value="HUKUM">S1 Hukum Adat</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kampus</label>
                    <select name="kampus" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        <option value="ITP Markandeya Bali">ITP Markandeya Bali (Pusat)</option>
                        <option value="PKMB Tabanan">PKMB Tabanan</option>
                        <option value="PKMB Widyagiri Petang">PKMB Widyagiri Petang</option>
                        <option value="PKBM Abiansemal">PKBM Abiansemal</option>
                    </select>
                </div>
            </div>

            <!-- Section 3: Program -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kegiatan</label>
                    <select name="kegiatan" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        <option value="KKN">KKN</option>
                        <option value="PPL">PPL</option>
                        <option value="PKL">PKL</option>
                        <option value="Magang">Magang</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Akademik</label>
                    <select name="tahun_akademik" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        @foreach($tahunAkademiks as $ta)
                            <option value="{{ $ta->tahun }} {{ $ta->semester }}" {{ $ta->is_active ? 'selected' : '' }}>
                                {{ $ta->tahun }} ({{ $ta->semester }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Lokasi Kecamatan</label>
                    <select name="kecamatan" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        <option value="Bangli">Bangli</option>
                        <option value="Kintamani">Kintamani</option>
                        <option value="Susut">Susut</option>
                        <option value="Gianyar">Gianyar</option>
                        <option value="Tegalalang">Tegalalang</option>
                        <option value="Ubud">Ubud</option>
                        <option value="Karangasem">Karangasem</option>
                        <option value="Klungkung">Klungkung</option>
                        <option value="Petang">Petang</option>
                        <option value="Abiansemal">Abiansemal</option>
                        <option value="Penebel">Penebel</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Aktif</label>
                <input type="email" name="email" required placeholder="email@markandeya.ac.id"
                    class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
            </div>

            <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100 flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <p class="text-xs text-blue-700 leading-relaxed font-medium">
                    <strong>Catatan:</strong> Mahasiswa yang ditambahkan manual oleh Admin akan memiliki password default berupa <strong>NIM</strong> mereka. Status pembayaran KRS dan KRS aktif akan otomatis ditandai sebagai <strong>Lunas/Aktif</strong> oleh sistem.
                </p>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-2 group transform hover:-translate-y-1">
                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Simpan Data Mahasiswa</span>
                </button>
                <button type="button" onclick="history.back()" class="px-10 py-5 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all uppercase tracking-widest text-xs">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
