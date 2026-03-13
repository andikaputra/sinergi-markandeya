@extends('layouts.adminmhs')

@section('title', 'Pengajuan Lokasi Magang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Status Pengajuan</h2>
            <p class="text-sm text-gray-500 font-medium">Pantau status persetujuan lokasi Magang mandiri Anda.</p>
        </div>
        <a href="{{ route('pengajuanmagang.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-100 group">
            <i class="fas fa-paper-plane mr-2 group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
            Ajukan Lokasi Baru
        </a>
    </div>

    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($pengajuans->isEmpty())
    <div class="bg-white p-16 rounded-[2.5rem] shadow-sm border border-gray-100 text-center">
        <div class="bg-slate-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
            <i class="fas fa-briefcase text-4xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800">Belum ada pengajuan</h3>
        <p class="text-gray-500 max-w-sm mx-auto mt-2">Silakan ajukan lokasi instansi atau perusahaan tempat Anda akan melaksanakan Magang.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($pengajuans as $item)
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-all">
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div class="bg-indigo-50 text-indigo-600 p-4 rounded-2xl">
                        <i class="fas fa-briefcase text-xl"></i>
                    </div>
                    @if ($item->status == 'pending')
                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black border border-amber-100 uppercase tracking-widest animate-pulse">Pending</span>
                    @elseif ($item->status == 'approved')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black border border-emerald-100 uppercase tracking-widest">Approved</span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black border border-red-100 uppercase tracking-widest">Rejected</span>
                    @endif
                </div>

                <h4 class="text-lg font-black text-gray-800 mb-2 leading-tight">{{ $item->nama_instansi }}</h4>

                <div class="space-y-3 mt-6">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-map-marker-alt w-5 text-indigo-400"></i>
                        <span class="font-medium">{{ $item->alamat }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-phone w-5 text-emerald-400"></i>
                        <span class="font-medium">{{ $item->kontak ?? 'Tidak ada kontak' }}</span>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-gray-50 flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <span>Dibuat pada:</span>
                <span>{{ $item->created_at->translatedFormat('d M Y') }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
