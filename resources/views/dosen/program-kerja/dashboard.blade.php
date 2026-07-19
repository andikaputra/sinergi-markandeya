@extends('layouts.main')

@section('title', 'Program Kerja Mahasiswa Bimbingan')

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Mahasiswa Bimbingan</p>
                    <p class="text-4xl font-black text-gray-900 mt-2">{{ $totalMahasiswa }}</p>
                </div>
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Program</p>
                    <p class="text-4xl font-black text-gray-900 mt-2">{{ $totalProgram }}</p>
                </div>
                <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                    <i class="fas fa-tasks text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Sudah Membuat Program</p>
                    <p class="text-4xl font-black text-gray-900 mt-2">{{ $mahasiswaDenganProgram }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ round(($mahasiswaDenganProgram / $totalMahasiswa) * 100) }}%</p>
                </div>
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Belum Membuat Program</p>
                    <p class="text-4xl font-black text-gray-900 mt-2">{{ $mahasiswaTanpaProgram }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ round(($mahasiswaTanpaProgram / $totalMahasiswa) * 100) }}%</p>
                </div>
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Status Program Kerja</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                <p class="text-sm font-bold text-blue-600 uppercase">Rencana</p>
                <p class="text-3xl font-black text-blue-900 mt-2">{{ $statistikStatus['rencana'] }}</p>
            </div>
            <div class="bg-orange-50 p-6 rounded-2xl border border-orange-100">
                <p class="text-sm font-bold text-orange-600 uppercase">Sedang Berjalan</p>
                <p class="text-3xl font-black text-orange-900 mt-2">{{ $statistikStatus['sedang_berjalan'] }}</p>
            </div>
            <div class="bg-green-50 p-6 rounded-2xl border border-green-100">
                <p class="text-sm font-bold text-green-600 uppercase">Selesai</p>
                <p class="text-3xl font-black text-green-900 mt-2">{{ $statistikStatus['selesai'] }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('dosen.program-kerja.mahasiswa') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 hover:shadow-lg transition-all duration-200 rounded-3xl shadow-lg text-white p-8 flex flex-col justify-between min-h-40">
            <div>
                <i class="fas fa-user-graduate text-3xl mb-4 opacity-80"></i>
                <h4 class="font-bold text-lg">Mahasiswa Bimbingan</h4>
            </div>
            <p class="text-sm opacity-90">{{ $totalMahasiswa }} mahasiswa</p>
        </a>

        <a href="{{ route('dosen.program-kerja.semua') }}" class="bg-gradient-to-br from-green-500 to-green-600 hover:shadow-lg transition-all duration-200 rounded-3xl shadow-lg text-white p-8 flex flex-col justify-between min-h-40">
            <div>
                <i class="fas fa-list-check text-3xl mb-4 opacity-80"></i>
                <h4 class="font-bold text-lg">Semua Program</h4>
            </div>
            <p class="text-sm opacity-90">{{ $totalProgram }} program</p>
        </a>

        <a href="{{ route('dosen.program-kerja.luaran') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 hover:shadow-lg transition-all duration-200 rounded-3xl shadow-lg text-white p-8 flex flex-col justify-between min-h-40">
            <div>
                <i class="fas fa-package text-3xl mb-4 opacity-80"></i>
                <h4 class="font-bold text-lg">Deliverables</h4>
            </div>
            <p class="text-sm opacity-90">Semua luaran</p>
        </a>
    </div>

    <!-- Recent Programs -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Program Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Mahasiswa (NIM)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Judul Program</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Dibuat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentPrograms as $program)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                {{ $program->mahasiswa->nama ?? '-' }}
                                <br>
                                <span class="text-xs text-gray-500">{{ $program->nim }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($program->judul, 40) }}</td>
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
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $program->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('dosen.program-kerja.detail', $program->mahasiswa) }}" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition inline-block">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada program kerja</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
