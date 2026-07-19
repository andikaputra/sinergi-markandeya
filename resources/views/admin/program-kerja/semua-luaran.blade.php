@extends('layouts.adminmhs')

@section('title', 'Semua Luaran (Deliverables)')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-900">Semua Luaran / Deliverables</h2>
            <p class="text-gray-500 mt-1">Total: {{ $luarans->total() }} luaran</p>
        </div>
        <a href="{{ route('admin.program-kerja.dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Status Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-red-50 rounded-2xl border border-red-100 p-6">
            <p class="text-xs font-bold text-red-600 uppercase">Belum Dikerjakan</p>
            <p class="text-3xl font-black text-red-900 mt-2">{{ $luarans->where('status', 'belum_dikerjakan')->count() }}</p>
        </div>
        <div class="bg-orange-50 rounded-2xl border border-orange-100 p-6">
            <p class="text-xs font-bold text-orange-600 uppercase">Sedang Dikerjakan</p>
            <p class="text-3xl font-black text-orange-900 mt-2">{{ $luarans->where('status', 'sedang_dikerjakan')->count() }}</p>
        </div>
        <div class="bg-green-50 rounded-2xl border border-green-100 p-6">
            <p class="text-xs font-bold text-green-600 uppercase">Selesai</p>
            <p class="text-3xl font-black text-green-900 mt-2">{{ $luarans->where('status', 'selesai')->count() }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Judul Luaran</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Mahasiswa (NIM)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Program Kerja</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Progress</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($luarans as $luaran)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ Str::limit($luaran->judul, 30) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.program-kerja.detail-mahasiswa', $luaran->programKerja->mahasiswa) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                    {{ $luaran->programKerja->mahasiswa->nama ?? '-' }}
                                    <br>
                                    <span class="text-xs text-gray-500">{{ $luaran->programKerja->nim }}</span>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($luaran->programKerja->judul ?? '-', 25) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($luaran->tipe) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $luaran->persentase_selesai }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600 w-10">{{ $luaran->persentase_selesai }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                    @if($luaran->status === 'belum_dikerjakan') bg-red-100 text-red-800
                                    @elseif($luaran->status === 'sedang_dikerjakan') bg-orange-100 text-orange-800
                                    @else bg-green-100 text-green-800
                                    @endif
                                ">
                                    {{ str_replace('_', ' ', ucfirst($luaran->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">Belum Ada Luaran</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $luarans->links() }}
    </div>
</div>
@endsection
