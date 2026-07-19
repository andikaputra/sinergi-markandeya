@extends('layouts.adminmhs')

@section('title', 'Semua Program Kerja')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-900">Semua Program Kerja</h2>
            <p class="text-gray-500 mt-1">Total: {{ $programs->total() }} program</p>
        </div>
        <a href="{{ route('admin.program-kerja.dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">NIM</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Mahasiswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Tanggal Mulai</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($programs as $program)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $program->nim }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.program-kerja.detail-mahasiswa', $program->mahasiswa) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                    {{ $program->mahasiswa->nama ?? '-' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="font-bold">{{ Str::limit($program->judul, 30) }}</div>
                                <div class="text-gray-500 text-xs">{{ $program->lokasi }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $program->tanggal_mulai->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                    @if($program->status === 'rencana') bg-blue-100 text-blue-800
                                    @elseif($program->status === 'sedang_berjalan') bg-orange-100 text-orange-800
                                    @elseif($program->status === 'selesai') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ str_replace('_', ' ', ucfirst($program->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.program-kerja.detail-mahasiswa', $program->mahasiswa) }}" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition inline-block">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">Belum Ada Program Kerja</p>
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
        {{ $programs->links() }}
    </div>
</div>
@endsection
