@extends('layouts.main')

@section('title', 'Detail Monitoring - ' . $program->judul)

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $program->judul }}</h1>
                <p class="text-gray-600 mt-2">{{ ucfirst($program->kategori) }} • {{ ucfirst($type) }}</p>
            </div>
            <a href="{{ route('dosen.program-kerja.monev-dashboard') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium">
                Kembali
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg">
            {{ $message }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Program Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Program</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Status</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">
                                @if ($program->status === 'rencana')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">Rencana</span>
                                @elseif ($program->status === 'sedang_berjalan')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full">Sedang Berjalan</span>
                                @elseif ($program->status === 'selesai')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">Selesai</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full">Tunda</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Lokasi</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">{{ $program->lokasi }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Tanggal Mulai</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">{{ $program->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Tanggal Selesai</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">{{ $program->tanggal_selesai->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold mb-2">Deskripsi</p>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $program->deskripsi }}</p>
                    </div>
                </div>
            </div>

            <!-- Luaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Luaran / Deliverable</h2>
                @if ($luarans->count() > 0)
                    <div class="space-y-4">
                        @foreach ($luarans as $luaran)
                            <div class="p-4 border border-gray-200 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $luaran->judul }}</h3>
                                        <p class="text-sm text-gray-600">{{ $luaran->tipe }} • {{ $luaran->tanggal_selesai->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm mb-3">{{ $luaran->deskripsi }}</p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="font-semibold text-gray-600">Progress: {{ $luaran->persentase_selesai }}%</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($luaran->status === 'belum_dikerjakan') bg-gray-100 text-gray-800
                                        @elseif($luaran->status === 'sedang_dikerjakan') bg-orange-100 text-orange-800
                                        @else bg-green-100 text-green-800
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', ucfirst($luaran->status)) }}
                                    </span>
                                </div>
                                @if ($luaran->file_path)
                                    <p class="text-xs mt-2"><a href="{{ $luaran->file_path }}" target="_blank" class="text-blue-600 hover:underline">Lihat File →</a></p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-6">Belum ada luaran</p>
                @endif
            </div>

            <!-- Anggota (Kelompok) -->
            @if ($type === 'kelompok' && isset($anggota))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Anggota Kelompok</h2>
                <div class="space-y-2">
                    @foreach ($anggota as $member)
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $member->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $member->nim }}</p>
                            </div>
                            @if ($member->nim === $program->nim_ketua)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Ketua</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar - Input Nilai -->
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sticky top-20">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Input Nilai Monev</h2>

                <form action="{{ route('dosen.program-kerja.monev-nilai', ['type' => $type, 'programId' => $program->id]) }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">Nilai (0-100)</label>
                        <input type="number" name="nilai" min="0" max="100" step="0.1" value="{{ old('nilai', $monev->nilai) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 @error('nilai') border-red-500 @enderror">
                        @error('nilai')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                        @if ($monev->nilai)
                            <p class="text-sm text-gray-600 mt-2">Nilai saat ini: <span class="font-bold">{{ $monev->nilai }}</span></p>
                        @endif
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">Catatan</label>
                        <textarea name="catatan" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 @error('catatan') border-red-500 @enderror">{{ old('catatan', $monev->catatan) }}</textarea>
                        @error('catatan')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        Simpan Nilai & Catatan
                    </button>
                </form>

                @if ($monev->nilai || $monev->catatan)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-xs text-gray-600 mb-2 font-semibold">Terakhir diupdate:</p>
                        <p class="text-sm text-gray-700">{{ $monev->updated_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
