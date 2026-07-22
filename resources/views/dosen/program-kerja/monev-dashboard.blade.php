@extends('layouts.main')

@section('title', 'Monitoring & Evaluasi Program Kerja')

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Monitoring & Evaluasi Program Kerja</h1>
        <p class="text-gray-600">Kelola program kerja yang Anda tugaskan untuk dimonitor</p>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg">
            {{ $message }}
        </div>
    @endif

    <!-- Program List -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Program yang Saya Monev</h2>

            @if ($monevPrograms->count() > 0)
                <div class="space-y-4">
                    @foreach ($monevPrograms as $monev)
                        @php
                            if ($monev->monev_type === 'individu') {
                                $program = App\Models\IndividuProgramKerja::find($monev->program_id);
                                $mahasiswa = $program?->mahasiswa;
                            } else {
                                $program = App\Models\KelompokProgramKerja::find($monev->program_id);
                                $mahasiswa = $program?->mahasiswaKetua;
                            }
                        @endphp
                        @if ($program)
                        <div class="p-6 border border-gray-200 rounded-2xl hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex gap-3 items-center mb-2">
                                        <h3 class="font-bold text-lg text-gray-900">{{ $program->judul }}</h3>
                                        <span class="px-3 py-1 bg-{{ $monev->monev_type === 'individu' ? 'blue' : 'purple' }}-100 text-{{ $monev->monev_type === 'individu' ? 'blue' : 'purple' }}-800 text-xs font-semibold rounded-full">
                                            {{ ucfirst($monev->monev_type) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ ucfirst($program->kategori) }} • {{ $program->tanggal_mulai->format('d M Y') }} - {{ $program->tanggal_selesai->format('d M Y') }}</p>
                                    @if ($mahasiswa)
                                        <p class="text-sm text-gray-700"><strong>{{ $monev->monev_type === 'individu' ? 'Mahasiswa' : 'Ketua' }}:</strong> {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</p>
                                    @endif
                                </div>

                                <div class="text-right">
                                    @if ($monev->nilai)
                                        <div class="mb-4">
                                            <p class="text-xs text-gray-600 mb-1">Nilai Monev</p>
                                            <p class="text-3xl font-bold text-blue-600">{{ $monev->nilai }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($program->status === 'rencana')
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full mb-4">Rencana</span>
                            @elseif ($program->status === 'sedang_berjalan')
                                <span class="inline-block px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full mb-4">Sedang Berjalan</span>
                            @elseif ($program->status === 'selesai')
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full mb-4">Selesai</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full mb-4">Tunda</span>
                            @endif

                            @if ($monev->catatan)
                                <div class="mb-4 p-3 bg-gray-50 border-l-4 border-gray-400 rounded">
                                    <p class="text-xs text-gray-600 font-semibold mb-1">Catatan Monev:</p>
                                    <p class="text-sm text-gray-700">{{ $monev->catatan }}</p>
                                </div>
                            @endif

                            <div class="flex gap-2 pt-4 border-t border-gray-200">
                                <a href="{{ route('dosen.program-kerja.monev-detail', ['type' => $monev->monev_type, 'programId' => $monev->program_id]) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition text-center text-sm">
                                    Lihat Detail & Input Nilai
                                </a>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($monevPrograms->hasPages())
                    <div class="mt-6">
                        {{ $monevPrograms->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-600 text-lg">Belum ada program yang ditugaskan untuk dimonev</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
