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
                    <p class="text-xs text-gray-400 mt-2 font-medium">
                        <span class="text-blue-600 font-bold">{{ $totalProgramIndividu ?? 0 }}</span> Individu &bull;
                        <span class="text-purple-600 font-bold">{{ $totalProgramKelompok ?? 0 }}</span> Kelompok
                    </p>
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
                    <p class="text-xs text-gray-400 mt-2">{{ $totalMahasiswa > 0 ? round(($mahasiswaDenganProgram / $totalMahasiswa) * 100) : 0 }}% dari mahasiswa bimbingan</p>
                </div>
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Belum Ada Program</p>
                    <p class="text-4xl font-black text-gray-900 mt-2">{{ $mahasiswaTanpaProgram }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $totalMahasiswa > 0 ? round(($mahasiswaTanpaProgram / $totalMahasiswa) * 100) : 0 }}% dari mahasiswa bimbingan</p>
                </div>
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Status Program Kerja (Individu & Kelompok)</h3>
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
            <p class="text-sm opacity-90">{{ $totalMahasiswa }} mahasiswa bimbingan</p>
        </a>

        <a href="{{ route('dosen.program-kerja.semua') }}" class="bg-gradient-to-br from-green-500 to-green-600 hover:shadow-lg transition-all duration-200 rounded-3xl shadow-lg text-white p-8 flex flex-col justify-between min-h-40">
            <div>
                <i class="fas fa-list-check text-3xl mb-4 opacity-80"></i>
                <h4 class="font-bold text-lg">Semua Program</h4>
            </div>
            <p class="text-sm opacity-90">{{ $totalProgram }} program (Individu & Kelompok)</p>
        </a>

        <a href="{{ route('dosen.program-kerja.luaran') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 hover:shadow-lg transition-all duration-200 rounded-3xl shadow-lg text-white p-8 flex flex-col justify-between min-h-40">
            <div>
                <i class="fas fa-box-open text-3xl mb-4 opacity-80"></i>
                <h4 class="font-bold text-lg">Deliverables / Luaran</h4>
            </div>
            <p class="text-sm opacity-90">Lihat berkas & luaran</p>
        </a>
    </div>

    <!-- Recent Programs -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center justify-between">
            <span>Program Kerja Terbaru</span>
            <a href="{{ route('dosen.program-kerja.semua') }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold flex items-center gap-1">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Mahasiswa / Ketua</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Judul Program</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Dibuat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentPrograms as $program)
                        @php
                            $isKelompok = ($program->program_type ?? 'individu') === 'kelompok';
                            $mhsTarget = $isKelompok ? $program->mahasiswaKetua : $program->mahasiswa;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                @if($isKelompok)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        <i class="fas fa-users mr-1.5 text-[10px]"></i> Kelompok
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fas fa-user mr-1.5 text-[10px]"></i> Individu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                {{ $mhsTarget->nama ?? '-' }}
                                <br>
                                <span class="text-xs text-gray-500 font-mono">{{ $program->nim ?? $program->nim_ketua }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="font-bold">{{ Str::limit($program->judul, 40) }}</div>
                                @if($program->lokasi)
                                    <div class="text-xs text-gray-400 font-medium mt-0.5"><i class="fas fa-map-marker-alt text-[10px] mr-1 text-gray-400"></i>{{ $program->lokasi }}</div>
                                @endif
                            </td>
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
                                @if($mhsTarget)
                                    <a href="{{ route('dosen.program-kerja.detail', $mhsTarget) }}" class="px-3.5 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition inline-flex items-center gap-1.5 shadow-sm">
                                        <i class="fas fa-eye text-[10px]"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl text-gray-400">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <p class="text-base font-bold text-gray-800">Belum Ada Program Kerja</p>
                                <p class="text-xs text-gray-400 mt-1">Program kerja individu maupun kelompok mahasiswa bimbingan akan ditampilkan di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
