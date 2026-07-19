@extends('layouts.admin')
@section('title', 'Kelola Akun Mahasiswa')
@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-800">Kelola Akun Mahasiswa</h2>
            <p class="text-sm text-gray-400 mt-1">Aktifkan atau nonaktifkan akun mahasiswa.</p>
        </div>
        <div class="flex items-center gap-3">
            @if($jumlahNonaktif > 0)
            <span class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-black border border-red-100">
                {{ $jumlahNonaktif }} akun nonaktif
            </span>
            @endif
            <a href="{{ route('admin.mahasiswa.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-100">
                <i class="fas fa-user-plus"></i>
                Tambah Mahasiswa
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center gap-2">
        <i class="fas fa-check-circle"></i>{{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/70">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Mahasiswa</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Prodi / Kampus</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($mahasiswas as $mhs)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 {{ $mhs->status === 'aktif' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-400' }} rounded-xl flex items-center justify-center font-black text-sm shrink-0">
                                    {{ substr($mhs->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $mhs->nama }}</p>
                                    <p class="text-xs text-gray-400">{{ $mhs->nim }} &bull; {{ $mhs->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-700">{{ $mhs->prodi }}</p>
                            <p class="text-xs text-gray-400">{{ $mhs->kampus }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($mhs->status === 'aktif')
                            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black border border-emerald-100 uppercase tracking-widest">Aktif</span>
                            @else
                            <div>
                                <span class="px-3 py-1.5 bg-red-50 text-red-600 rounded-xl text-[10px] font-black border border-red-100 uppercase tracking-widest">Nonaktif</span>
                                @if($mhs->catatan_penolakan)
                                <p class="text-[10px] text-gray-400 mt-1 max-w-[160px] truncate">{{ $mhs->catatan_penolakan }}</p>
                                @endif
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($mhs->status === 'aktif')
                            {{-- Nonaktifkan --}}
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                    onclick="document.getElementById('form-nonaktif-{{ $mhs->id }}').classList.toggle('hidden')"
                                    class="px-4 py-2 bg-red-50 hover:bg-red-500 hover:text-white text-red-600 text-xs font-bold rounded-xl transition-all">
                                    <i class="fas fa-ban mr-1"></i> Nonaktifkan
                                </button>
                            </div>
                            <div id="form-nonaktif-{{ $mhs->id }}" class="hidden mt-2">
                                <form action="{{ route('admin.mahasiswa.reject', $mhs->id) }}" method="POST" class="space-y-2 p-3 bg-red-50/50 rounded-2xl border border-red-100">
                                    @csrf
                                    <textarea name="catatan" rows="1" placeholder="Alasan (opsional)"
                                        class="w-full px-3 py-2 bg-white border border-red-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:border-red-400 resize-none"></textarea>
                                    <button type="submit" class="w-full py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-xl transition-all">
                                        Konfirmasi
                                    </button>
                                </form>
                            </div>

                            @else
                            {{-- Aktifkan kembali --}}
                            <form action="{{ route('admin.mahasiswa.approve', $mhs->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                    <i class="fas fa-check mr-1"></i> Aktifkan
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada mahasiswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $mahasiswas->links() }}
        </div>
    </div>
</div>
@endsection
