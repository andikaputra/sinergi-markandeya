@extends('layouts.admin')

@section('title', 'Tambah Pembimbing Luar')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('pembimbing_luar.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:border-emerald-600 transition-all">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Pembimbing Luar</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden p-8 sm:p-12">
        @if ($errors->any())
        <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('pembimbing_luar.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all"
                    placeholder="Masukkan nama lengkap">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all"
                    placeholder="Masukkan email (digunakan untuk login)">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Instansi / Lembaga</label>
                <input type="text" name="instansi" value="{{ old('instansi') }}" required
                    class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all"
                    placeholder="Nama instansi atau lembaga asal">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">No. HP (Opsional)</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                    class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all"
                    placeholder="Nomor HP">
            </div>

            <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl text-amber-700 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Password default: <strong>markandeyabali{{ date('Y') }}</strong>. Pembimbing luar dapat mengubah password setelah login.
            </div>

            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
                <i class="fas fa-save mr-2"></i> Simpan Pembimbing Luar
            </button>
        </form>
    </div>
</div>
@endsection
