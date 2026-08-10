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

    {{-- Search Form --}}
    <form method="GET" action="{{ route('admin.mahasiswa.pending') }}" class="flex items-center gap-3 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                <i class="fas fa-search text-sm"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, NIM, atau email mahasiswa..."
                   class="w-full bg-slate-50 border border-gray-100 text-gray-700 placeholder-gray-400 rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all">
        </div>
        <button type="submit" class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-blue-100">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.mahasiswa.pending') }}" class="px-5 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold rounded-2xl transition-all">
                Reset
            </a>
        @endif
    </form>

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
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                @if($mhs->status === 'aktif')
                                <button type="button"
                                    onclick="document.getElementById('form-nonaktif-{{ $mhs->id }}').classList.toggle('hidden'); document.getElementById('form-assign-{{ $mhs->id }}').classList.add('hidden')"
                                    class="px-3 py-1.5 bg-red-50 hover:bg-red-500 hover:text-white text-red-600 text-xs font-bold rounded-xl transition-all">
                                    <i class="fas fa-ban mr-1"></i> Nonaktifkan
                                </button>
                                @else
                                <form action="{{ route('admin.mahasiswa.approve', $mhs->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                        <i class="fas fa-check mr-1"></i> Aktifkan
                                    </button>
                                </form>
                                @endif

                                <button type="button"
                                    onclick="document.getElementById('form-assign-{{ $mhs->id }}').classList.toggle('hidden'); document.getElementById('form-nonaktif-{{ $mhs->id }}')?.classList.add('hidden')"
                                    class="px-3 py-1.5 bg-blue-50 hover:bg-blue-500 hover:text-white text-blue-600 text-xs font-bold rounded-xl transition-all">
                                    <i class="fas fa-clipboard-list mr-1"></i> Plot
                                </button>

                                <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}"
                                    class="px-3 py-1.5 bg-amber-50 hover:bg-amber-500 hover:text-white text-amber-600 text-xs font-bold rounded-xl transition-all">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            </div>

                            {{-- Form Nonaktifkan --}}
                            @if($mhs->status === 'aktif')
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
                            @endif

                            {{-- Form Assign Kegiatan --}}
                            <div id="form-assign-{{ $mhs->id }}" class="hidden mt-2">
                                <form action="{{ route('admin.mahasiswa.assign-kegiatan') }}" method="POST" class="space-y-3 p-4 bg-blue-50/50 rounded-2xl border border-blue-100 text-left">
                                    @csrf
                                    <input type="hidden" name="nim" value="{{ $mhs->nim }}">
                                    
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block ml-1">Pilih Kegiatan</label>
                                        <select name="kegiatan" required class="w-full px-3 py-2 bg-white border border-blue-100 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 font-bold">
                                            <option value="KKN" {{ $mhs->kegiatan === 'KKN' ? 'selected' : '' }}>KKN (Kuliah Kerja Nyata)</option>
                                            <option value="PPL" {{ $mhs->kegiatan === 'PPL' ? 'selected' : '' }}>PPL (Praktik Pengalaman Lapangan)</option>
                                            <option value="PKL" {{ $mhs->kegiatan === 'PKL' ? 'selected' : '' }}>PKL (Praktik Kerja Lapangan)</option>
                                            <option value="Magang" {{ $mhs->kegiatan === 'Magang' ? 'selected' : '' }}>Magang</option>
                                        </select>
                                    </div>
                                    
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block ml-1">Tahun Akademik</label>
                                        <select name="tahun_akademik" required class="w-full px-3 py-2 bg-white border border-blue-100 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 font-bold">
                                            @foreach($tahunAkademiks as $ta)
                                                <option value="{{ $ta->tahun }} {{ $ta->semester }}" {{ $mhs->tahun_akademik === ($ta->tahun . ' ' . $ta->semester) ? 'selected' : '' }}>
                                                    {{ $ta->tahun }} - {{ $ta->semester }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black rounded-xl transition-all uppercase tracking-wider">
                                        Simpan Plotting
                                    </button>
                                </form>
                            </div>
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
