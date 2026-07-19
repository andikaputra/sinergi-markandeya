@extends('layouts.adminmhs')

@section('title', 'Mahasiswa Tanpa Program Kerja')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-900">Mahasiswa Tanpa Program Kerja</h2>
            <p class="text-gray-500 mt-1">{{ $mahasiswaTanpaProgram->total() }} mahasiswa</p>
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Program Studi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status Kegiatan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Terdaftar Sejak</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($mahasiswaTanpaProgram as $mahasiswa)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $mahasiswa->nim }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $mahasiswa->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $mahasiswa->prodi_full ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                    @if($mahasiswa->kegiatan) bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ $mahasiswa->kegiatan ?? 'Belum Terdaftar' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $mahasiswa->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.program-kerja.detail-mahasiswa', $mahasiswa) }}" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-check text-3xl text-green-600"></i>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">Semua Mahasiswa Sudah Ada Program Kerja!</p>
                                    <p class="text-gray-500 mt-1">Wah, prestasi yang luar biasa!</p>
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
        {{ $mahasiswaTanpaProgram->links() }}
    </div>
</div>
@endsection
