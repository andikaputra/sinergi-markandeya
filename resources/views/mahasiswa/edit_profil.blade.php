@extends('layouts.adminmhs')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-black text-gray-800">Edit Profil</h2>
        <p class="text-sm text-gray-400 mt-1">Perbarui informasi data diri Anda. NIM tidak dapat diubah.</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-sm font-bold">
        <div class="flex items-center mb-2"><i class="fas fa-exclamation-circle mr-2"></i>Ada kesalahan input:</div>
        <ul class="list-disc ml-6 space-y-1 text-xs font-medium">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-2xl font-black text-blue-600">
                {{ substr($mahasiswa->nama, 0, 1) }}
            </div>
            <div>
                <p class="font-black text-gray-800">{{ $mahasiswa->nama }}</p>
                <p class="text-sm text-gray-400">NIM: <strong class="text-gray-600">{{ $mahasiswa->nim }}</strong></p>
            </div>
        </div>

        <form action="{{ route('mahasiswa.profil.update') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required
                        class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM</label>
                    <input type="text" value="{{ $mahasiswa->nim }}" disabled
                        class="block w-full px-5 py-4 bg-gray-100 border border-gray-100 rounded-2xl text-gray-400 font-bold cursor-not-allowed">
                    <p class="text-[10px] text-gray-400 ml-1">NIM tidak dapat diubah</p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Aktif</label>
                <input type="email" name="email" value="{{ old('email', $mahasiswa->email) }}" required
                    class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kampus</label>
                    <select name="kampus" required class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        @foreach(['Universitas Markandeya' => 'Universitas Markandeya (Pusat)', 'PKMB Tabanan' => 'PKMB Tabanan', 'PKMB Widyagiri Petang' => 'PKMB Widyagiri Petang', 'PKBM Abiansemal' => 'PKBM Abiansemal'] as $val => $label)
                        <option value="{{ $val }}" {{ old('kampus', $mahasiswa->kampus) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Program Studi</label>
                    <select name="prodi" required class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                        @foreach(['PGSD' => 'S1 Pendidikan Guru Sekolah Dasar', 'PBSI' => 'S1 Pendidikan Bahasa dan Sastra Indonesia', 'PBI' => 'S1 Pendidikan Bahasa Inggris', 'SI' => 'S1 Sistem Informasi', 'ME' => 'S1 Manajemen Ekonomi', 'PARBUD' => 'S1 Pariwisata Budaya Dan Keagamaan', 'HUKUM' => 'S1 Hukum Adat'] as $val => $label)
                        <option value="{{ $val }}" {{ old('prodi', $mahasiswa->prodi) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Lokasi Kecamatan</label>
                <select name="kecamatan" required class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                    @foreach(['Bangli','Kintamani','Susut','Gianyar','Tegalalang','Ubud','Karangasem','Klungkung','Petang','Abiansemal','Penebel'] as $kec)
                    <option value="{{ $kec }}" {{ old('kecamatan', $mahasiswa->kecamatan) === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
            </div>

            <div class="p-6 bg-blue-50/50 rounded-3xl space-y-4 border border-blue-100">
                <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest flex items-center">
                    <i class="fas fa-file-alt mr-2"></i> Tautan Dokumen Pendukung
                </h4>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Link Bukti Pembayaran KRS</label>
                    <input type="text" name="pembayaranKRS" value="{{ old('pembayaranKRS', $mahasiswa->pembayaranKRS) }}" required
                        placeholder="https://drive.google.com/..."
                        class="block w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-medium">
                    @error('pembayaranKRS')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Link Dokumen KRS</label>
                    <input type="text" name="KRS" value="{{ old('KRS', $mahasiswa->KRS) }}" required
                        placeholder="https://drive.google.com/..."
                        class="block w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-medium">
                    @error('KRS')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-4 pt-2">
                <button type="submit" class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all uppercase tracking-widest text-xs">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-2xl transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
