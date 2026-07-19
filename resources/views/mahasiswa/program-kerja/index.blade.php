@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Program Kerja Saya</h1>
        <p class="text-gray-300">Kelola program kerja dan luaran (deliverable) Anda</p>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ $message }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #d4a574;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Total Program</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $statistik['total'] }}</p>
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
                    <p class="text-3xl font-bold text-gray-800">{{ $statistik['rencana'] }}</p>
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
                    <p class="text-3xl font-bold text-gray-800">{{ $statistik['sedang_berjalan'] }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6" style="border-left: 4px solid #1a5d4d;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Selesai</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $statistik['selesai'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <div class="mb-6">
        <a href="{{ route('program-kerja.create') }}" class="inline-block px-6 py-3 text-white font-semibold rounded-lg transition duration-200" style="background-color: #d4a574; color: #0f2d26;" onmouseover="this.style.backgroundColor='#c9905c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.backgroundColor='#d4a574'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Program Kerja
        </a>
    </div>

    <!-- Programs Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0f2d26;">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white">Judul</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white">Tanggal Mulai</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($programKerjas as $program)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <p class="text-gray-900 font-medium">{{ $program->judul }}</p>
                                <p class="text-gray-600 text-sm">{{ Str::limit($program->deskripsi, 50) }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $program->tanggal_mulai->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                    @if($program->status === 'rencana') bg-blue-100 text-blue-800
                                    @elseif($program->status === 'sedang_berjalan') bg-orange-100 text-orange-800
                                    @elseif($program->status === 'selesai') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $program->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('program-kerja.show', $program) }}" class="inline-block px-3 py-1 text-sm rounded transition" style="background-color: #d4a574; color: #0f2d26;" onmouseover="this.style.backgroundColor='#c9905c';" onmouseout="this.style.backgroundColor='#d4a574';">
                                    Lihat
                                </a>
                                <a href="{{ route('program-kerja.edit', $program) }}" class="inline-block px-3 py-1 text-sm rounded transition" style="background-color: #1a5d4d; color: white;" onmouseover="this.style.backgroundColor='#0f2d26';" onmouseout="this.style.backgroundColor='#1a5d4d';">
                                    Edit
                                </a>
                                <form action="{{ route('program-kerja.destroy', $program) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm rounded transition bg-red-600 text-white" onmouseover="this.style.backgroundColor='#dc2626';" onmouseout="this.style.backgroundColor='#ef4444';">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-600">
                                <p class="mb-4">Anda belum memiliki program kerja</p>
                                <a href="{{ route('program-kerja.create') }}" class="inline-block px-4 py-2 text-white rounded transition" style="background-color: #d4a574; color: #0f2d26;">
                                    Buat Program Kerja Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $programKerjas->links() }}
    </div>
</div>
@endsection
