@extends('layouts.main')

@section('title', 'Mahasiswa Bimbingan')

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-900">Mahasiswa Bimbingan</h2>
            <p class="text-gray-500 mt-1">Total: {{ $mahasiswaBimbingan->total() }} mahasiswa</p>
        </div>
        <a href="{{ route('dosen.program-kerja.dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">NIM</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Program Studi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Program Kerja</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($mahasiswaBimbingan as $mahasiswa)
                        @php
                            $programCount = \App\Models\ProgramKerja::where('nim', $mahasiswa->nim)->count();
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $mahasiswa->nim }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $mahasiswa->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($mahasiswa->prodi_full ?? '-', 30) }}</td>
                            <td class="px-6 py-4">
                                @if ($programCount > 0)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        {{ $programCount }} program
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        Belum ada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('dosen.program-kerja.detail', $mahasiswa) }}" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition inline-block">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">Belum Ada Mahasiswa Bimbingan</p>
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
        {{ $mahasiswaBimbingan->links() }}
    </div>
</div>
@endsection
