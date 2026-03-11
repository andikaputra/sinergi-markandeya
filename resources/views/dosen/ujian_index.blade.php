@extends('layouts.dosen')

@section('title', 'Daftar Mahasiswa Ujian')

@section('content')
<div class="space-y-8">
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-6">
            <div class="bg-indigo-600 p-5 rounded-2xl shadow-lg shadow-indigo-200">
                <i class="fas fa-gavel text-white text-3xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Panel Penguji</h3>
                <p class="text-sm text-gray-500 font-medium">Anda ditugaskan menguji <span class="font-bold text-indigo-600">{{ $mahasiswaUjian->count() }}</span> mahasiswa.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Mahasiswa</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Program</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Publikasi Luaran</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Input Nilai Ujian</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach ($mahasiswaUjian as $item)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center font-black">
                                    {{ substr($item->mahasiswa->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $item->mahasiswa->nama }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $item->mahasiswa->nim }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase">
                                {{ $item->mahasiswa->kegiatan }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($item->mahasiswa->publikasis->isNotEmpty())
                                @foreach($item->mahasiswa->publikasis as $pub)
                                    <a href="{{ $pub->link }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-600 hover:text-white transition-all mb-1">
                                        <i class="fas fa-external-link-alt mr-1"></i> Lihat Artikel
                                    </a>
                                @endforeach
                            @else
                                <span class="text-[10px] text-gray-400 italic font-medium">Belum Ada</span>
                            @endif
                        </td>
                            <form action="{{ route('dosen.ujian.nilai', $item->mahasiswa->nim) }}" method="POST" class="flex items-center justify-center space-x-2">
                                @csrf
                                <input type="text" name="nilai" value="{{ $item->nilai }}" 
                                    class="w-20 px-3 py-2 bg-slate-50 border border-gray-200 rounded-xl text-center font-black text-indigo-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="...">
                                <button type="submit" class="p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-md shadow-indigo-100 transition-all">
                                    <i class="fas fa-save text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
