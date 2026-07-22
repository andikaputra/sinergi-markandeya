@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Program Kerja {{ ucfirst($kegiatan) }}</h1>
        <p class="text-gray-300">Kelola program kerja individu dan kelompok serta luaran (deliverable)</p>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ $message }}
        </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="mb-8 border-b border-gray-700">
        <div class="flex gap-8">
            <button onclick="switchTab('individu')" id="tab-individu" class="px-4 py-3 border-b-2 border-yellow-600 text-yellow-600 font-semibold cursor-pointer">
                Program Individu
            </button>
            <button onclick="switchTab('kelompok')" id="tab-kelompok" class="px-4 py-3 border-b-2 border-transparent text-gray-400 font-semibold cursor-pointer hover:text-gray-300">
                Program Kelompok
            </button>
        </div>
    </div>

    <!-- Program Individu Tab -->
    <div id="content-individu" class="tab-content">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #d4a574;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Total Program</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistikIndividu['total'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1a5d4d;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Rencana</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistikIndividu['rencana'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #d4a574;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Sedang Berjalan</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistikIndividu['sedang_berjalan'] }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #3b8686;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Selesai</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistikIndividu['selesai'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <a href="{{ route('program-kerja.create-individu') }}" class="inline-block px-6 py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition">
                + Buat Program Individu
            </a>
        </div>

        <!-- Programs Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Judul</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Mulai</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($individuPrograms as $program)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-800">{{ Str::limit($program->judul, 40) }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $program->tanggal_mulai->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if ($program->status === 'rencana')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Rencana</span>
                                @elseif ($program->status === 'sedang_berjalan')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">Sedang Berjalan</span>
                                @elseif ($program->status === 'selesai')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Selesai</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Tunda</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('program-kerja.show-individu', $program) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                    <a href="{{ route('program-kerja.edit-individu', $program) }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Edit</a>
                                    <form action="{{ route('program-kerja.destroy-individu', $program) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada program kerja individu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($individuPrograms->hasPages())
            <div class="mt-6">
                {{ $individuPrograms->links() }}
            </div>
        @endif
    </div>

    <!-- Program Kelompok Tab -->
    <div id="content-kelompok" class="tab-content hidden">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #d4a574;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Total Program</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistikKelompok['total'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1a5d4d;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Rencana</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistikKelompok['rencana'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #d4a574;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Sedang Berjalan</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistikKelompok['sedang_berjalan'] }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #3b8686;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Selesai</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistikKelompok['selesai'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <a href="{{ route('program-kerja.create-kelompok') }}" class="inline-block px-6 py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition">
                + Buat Program Kelompok
            </a>
        </div>

        <!-- Programs Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Judul</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ketua</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Mulai</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelompokPrograms as $program)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-800">{{ Str::limit($program->judul, 40) }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $program->mahasiswaKetua->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $program->tanggal_mulai->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if ($program->status === 'rencana')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Rencana</span>
                                @elseif ($program->status === 'sedang_berjalan')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">Sedang Berjalan</span>
                                @elseif ($program->status === 'selesai')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Selesai</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Tunda</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('program-kerja.show-kelompok', $program) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                    @if (auth('mahasiswa')->user()->nim === $program->nim_ketua)
                                        <a href="{{ route('program-kerja.edit-kelompok', $program) }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Edit</a>
                                        <form action="{{ route('program-kerja.destroy-kelompok', $program) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada program kerja kelompok di lokasi Anda</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kelompokPrograms->hasPages())
            <div class="mt-6">
                {{ $kelompokPrograms->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function switchTab(tab) {
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id^="tab-"]').forEach(el => {
        el.classList.remove('border-yellow-600', 'text-yellow-600');
        el.classList.add('border-transparent', 'text-gray-400');
    });

    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.add('border-yellow-600', 'text-yellow-600');
    document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-400');
}
</script>
@endsection
