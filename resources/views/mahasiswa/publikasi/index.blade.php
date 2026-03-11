@extends('layouts.adminmhs')

@section('title', 'Daftar Publikasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Publikasi Karya</h2>
            <p class="text-sm text-gray-500">Kelola tautan publikasi ilmiah atau artikel hasil kegiatan Anda.</p>
        </div>
        <a href="{{ route('publikasi.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-lg shadow-emerald-200 group">
            <i class="fas fa-upload mr-2 group-hover:-translate-y-1 transition-transform"></i>
            Tambah Publikasi
        </a>
    </div>

    @if($publikasis->isEmpty())
    <div class="bg-white p-12 rounded-3xl shadow-sm border border-gray-100 text-center">
        <div class="bg-emerald-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-300">
            <i class="fas fa-paper-plane text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Belum ada publikasi</h3>
        <p class="text-gray-500 max-w-xs mx-auto mt-2">Bagikan hasil karya atau artikel Anda dengan menambahkan publikasi baru.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($publikasis as $publikasi)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-all duration-300">
            <div class="p-6 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-blue-50 text-blue-600 p-3 rounded-2xl">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <form action="{{ route('publikasi.destroy', $publikasi->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-2" onclick="return confirm('Yakin ingin menghapus publikasi ini?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 leading-tight">{{ $publikasi->judul }}</h4>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-4">Tautan Publikasi</p>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 mb-4 overflow-hidden">
                    <p class="text-xs text-blue-500 truncate font-mono italic">{{ $publikasi->link }}</p>
                </div>
            </div>
            <div class="p-4 bg-gray-50/50 border-t border-gray-100">
                <a href="{{ $publikasi->link }}" target="_blank" class="flex items-center justify-center w-full px-4 py-3 bg-white border border-gray-200 text-blue-600 font-bold rounded-xl hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 shadow-sm">
                    <span>Lihat Publikasi</span>
                    <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
