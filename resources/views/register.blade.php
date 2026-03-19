@extends('layouts.main')

@section('title', 'Pendaftaran Mahasiswa Baru')

{{-- Menghilangkan Sidebar untuk halaman register --}}
<style>
    #sidebar, header, footer { display: none !important; }
    main { margin-left: 0 !important; width: 100% !important; background: white !important; }
</style>

@section('content')
<div class="min-h-screen py-12 px-4 relative overflow-hidden bg-white">
    <!-- Background Gradients -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-50/50 rounded-full blur-[120px] -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-indigo-50/50 rounded-full blur-[120px] -ml-48 -mb-48"></div>

    <div class="max-w-2xl mx-auto relative z-10">
        <!-- Logo & Header -->
        <div class="text-center mb-10">
            <img src="https://markandeyabali.ac.id/wp-content/uploads/2023/06/cropped-cropped-logo-1.png" alt="Logo Markandeya" class="h-20 w-auto mx-auto mb-6 transform hover:scale-105 transition-transform">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Formulir Pendaftaran</h2>
            <p class="text-gray-400 mt-2 font-bold uppercase tracking-[0.2em] text-[10px]">Portal Akademik Sinergi Markandeya</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-2xl hover:shadow-blue-900/5">
            <div class="p-8 sm:p-12">
                @if (session('success'))
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- Personal Info Section -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Lengkap</label>
                                <input type="text" name="nama" required placeholder="Nama sesuai KTP"
                                    class="block w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">NIM</label>
                                <input type="text" name="nim" required placeholder="Nomor Induk Mahasiswa"
                                    class="block w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Pilih Kampus</label>
                                <select name="kampus" required class="block w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                                    <option value="ITP Markandeya Bali">ITP Markandeya Bali (Pusat)</option>
                                    <option value="PKMB Tabanan">PKMB Tabanan</option>
                                    <option value="PKMB Widyagiri Petang">PKMB Widyagiri Petang</option>
                                    <option value="PKBM Abiansemal">PKBM Abiansemal</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Program Studi</label>
                                <select name="prodi" required class="block w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
                                    <option value="PGSD">S1 Pendidikan Guru Sekolah Dasar</option>
                                    <option value="PBSI">S1 Pendidikan Bahasa dan Sastra Indonesia</option>
                                    <option value="PBI">S1 Pendidikan Bahasa Inggris</option>
                                    <option value="SI">S1 Sistem Informasi</option>
                                    <option value="ME">S1 Manajemen Ekonomi</option>
                                    <option value="PARBUD">S1 Pariwisata Budaya Dan Keagamaan</option>
                                    <option value="HUKUM">S1 Hukum Adat</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Lokasi Kecamatan</label>
                            <select name="kecamatan" required class="block w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold appearance-none">
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

                    <!-- Documents Section -->
                    <div class="p-8 bg-blue-50/50 rounded-3xl space-y-6 border border-blue-100">
                        <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest flex items-center">
                            <i class="fas fa-file-alt mr-2"></i> Tautan Dokumen Pendukung
                        </h4>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em]">Link Bukti Pembayaran KRS</label>
                                <input type="text" name="pembayaranKRS" required placeholder="https://drive.google.com/..."
                                    class="block w-full px-6 py-4 bg-white border border-blue-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-medium">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em]">Link Dokumen KRS</label>
                                <input type="text" name="KRS" required placeholder="https://drive.google.com/..."
                                    class="block w-full px-6 py-4 bg-white border border-blue-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="space-y-6 pt-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Aktif</label>
                            <input type="email" name="email" required placeholder="email@contoh.com"
                                class="block w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Password</label>
                                <input type="password" name="password" required placeholder="••••••••"
                                    class="block w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" required placeholder="••••••••"
                                    class="block w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-black rounded-[1.5rem] shadow-xl shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98] tracking-widest uppercase text-xs">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>

            <div class="p-8 bg-gray-50 border-t border-gray-50 text-center">
                <p class="text-sm text-gray-500 font-medium">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline ml-1">Masuk di sini</a></p>
            </div>
        </div>
        
        <p class="mt-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-[0.4em]">
            System Kernel &bull; {{ date('Y') }}
        </p>
    </div>
</div>
@endsection
