@extends('layouts.admin')

@section('title', 'Manajemen Tahun Akademik')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Period Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-slate-50/50">
                    <h3 class="text-lg font-black text-gray-800 tracking-tight">Tambah Periode</h3>
                    <p class="text-xs text-gray-400 font-medium">Buat rentang tahun akademik baru.</p>
                </div>
                <form action="{{ route('tahun_akademik.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Akademik</label>
                        <input type="text" name="tahun" required placeholder="Contoh: 2025/2026"
                            class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Semester</label>
                        <select name="semester" required class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-bold appearance-none">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-plus-circle text-sm"></i>
                        <span class="uppercase tracking-widest text-xs">Simpan Periode</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Period List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-black text-gray-800 tracking-tight">Daftar Periode</h3>
                    <div class="bg-blue-50 px-4 py-1 rounded-full text-[10px] font-black text-blue-600 uppercase">Master Data</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tahun Akademik</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Semester</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tahunAkademiks as $ta)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5 font-bold text-gray-700">{{ $ta->tahun }}</td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase">{{ $ta->semester }}</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($ta->is_active)
                                        <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black border border-emerald-100 uppercase tracking-widest shadow-sm shadow-emerald-50">Aktif</span>
                                    @else
                                        <span class="px-4 py-1.5 bg-slate-50 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if(!$ta->is_active)
                                        <form action="{{ route('tahun_akademik.active', $ta->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Set Aktif">
                                                <i class="fas fa-check-circle text-xs"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('tahun_akademik.delete', $ta->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Hapus periode ini?')">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                        @else
                                            <span class="text-[10px] font-bold text-emerald-500 italic mr-2">Current Active</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
