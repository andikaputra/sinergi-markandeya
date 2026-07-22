@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $kelompokProgramKerja->judul }}</h1>
            <p class="text-gray-300">{{ ucfirst($kelompokProgramKerja->kategori) }} | {{ $kelompokProgramKerja->tanggal_mulai->format('d M Y') }} - {{ $kelompokProgramKerja->tanggal_selesai->format('d M Y') }}</p>
        </div>
        @if (auth('mahasiswa')->user()->nim === $kelompokProgramKerja->nim_ketua)
        <div class="flex gap-2">
            <a href="{{ route('program-kerja.edit-kelompok', $kelompokProgramKerja) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Edit</a>
            <form action="{{ route('program-kerja.destroy-kelompok', $kelompokProgramKerja) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Hapus</button>
            </form>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Program Details -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Deskripsi Program</h2>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $kelompokProgramKerja->deskripsi }}</p>
            </div>

            <!-- Anggota Kelompok -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Anggota Kelompok ({{ $anggota->count() }})</h2>
                <div class="space-y-3">
                    @foreach ($anggota as $member)
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $member->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $member->nim }}</p>
                            </div>
                            @if ($member->nim === $kelompokProgramKerja->nim_ketua)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Ketua</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Luaran Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Luaran / Deliverable</h2>
                    @if (in_array(auth('mahasiswa')->user()->nim, $anggota->pluck('nim')->toArray()) || auth('mahasiswa')->user()->nim === $kelompokProgramKerja->nim_ketua)
                        <button onclick="toggleForm('luaran-form')" class="px-4 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700">+ Tambah Luaran</button>
                    @endif
                </div>

                <!-- Form Tambah Luaran -->
                @if (in_array(auth('mahasiswa')->user()->nim, $anggota->pluck('nim')->toArray()) || auth('mahasiswa')->user()->nim === $kelompokProgramKerja->nim_ketua)
                <div id="luaran-form" class="hidden mb-6 p-4 bg-gray-50 border border-gray-300 rounded-lg">
                    <form action="{{ route('luaran.store-kelompok', $kelompokProgramKerja) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Judul Luaran</label>
                            <input type="text" name="judul" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Tipe</label>
                                <input type="text" name="tipe" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Dokumen, Laporan, etc." required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Link File / URL</label>
                            <input type="url" name="file_path" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="https://...">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan</button>
                            <button type="button" onclick="toggleForm('luaran-form')" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">Batal</button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Luaran List -->
                @if ($luarans->count() > 0)
                    <div class="space-y-4">
                        @foreach ($luarans as $luaran)
                            <div class="p-4 border border-gray-200 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $luaran->judul }}</h3>
                                        <p class="text-sm text-gray-600">{{ $luaran->tipe }} • {{ $luaran->tanggal_selesai->format('d M Y') }}</p>
                                    </div>
                                    @if (in_array(auth('mahasiswa')->user()->nim, $anggota->pluck('nim')->toArray()) || auth('mahasiswa')->user()->nim === $kelompokProgramKerja->nim_ketua)
                                    <form action="{{ route('luaran.destroy', ['type' => 'kelompok', 'luaranId' => $luaran->id]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                    </form>
                                    @endif
                                </div>
                                <p class="text-gray-700 text-sm mb-3">{{ Str::limit($luaran->deskripsi, 100) }}</p>
                                @if (in_array(auth('mahasiswa')->user()->nim, $anggota->pluck('nim')->toArray()) || auth('mahasiswa')->user()->nim === $kelompokProgramKerja->nim_ketua)
                                <form action="{{ route('luaran.update-status', ['type' => 'kelompok', 'luaranId' => $luaran->id]) }}" method="POST" class="space-y-2">
                                    @csrf @method('PUT')
                                    <div class="flex gap-4">
                                        <div class="flex-1">
                                            <label class="text-sm text-gray-600">Status</label>
                                            <select name="status" class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                                <option value="belum_dikerjakan" @selected($luaran->status === 'belum_dikerjakan')>Belum Dikerjakan</option>
                                                <option value="sedang_dikerjakan" @selected($luaran->status === 'sedang_dikerjakan')>Sedang Dikerjakan</option>
                                                <option value="selesai" @selected($luaran->status === 'selesai')>Selesai</option>
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label class="text-sm text-gray-600">% Selesai</label>
                                            <input type="number" name="persentase_selesai" min="0" max="100" value="{{ $luaran->persentase_selesai }}" class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Update</button>
                                        </div>
                                    </div>
                                </form>
                                @else
                                <div class="flex gap-4 text-sm">
                                    <span>
                                        @if ($luaran->status === 'belum_dikerjakan')
                                            <span class="text-blue-600">Belum Dikerjakan</span>
                                        @elseif ($luaran->status === 'sedang_dikerjakan')
                                            <span class="text-orange-600">Sedang Dikerjakan ({{ $luaran->persentase_selesai }}%)</span>
                                        @else
                                            <span class="text-green-600">Selesai</span>
                                        @endif
                                    </span>
                                </div>
                                @endif
                                @if ($luaran->file_path)
                                    <p class="text-xs mt-2"><a href="{{ $luaran->file_path }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-6">Belum ada luaran</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Status Program</h3>
                <div class="mb-4">
                    @if ($kelompokProgramKerja->status === 'rencana')
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Rencana</span>
                    @elseif ($kelompokProgramKerja->status === 'sedang_berjalan')
                        <span class="inline-block px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">Sedang Berjalan</span>
                    @elseif ($kelompokProgramKerja->status === 'selesai')
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Selesai</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Tunda</span>
                    @endif
                </div>

                <div class="space-y-3 text-sm text-gray-700">
                    <div>
                        <p class="font-semibold">Lokasi</p>
                        <p>{{ $kelompokProgramKerja->lokasi }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Tanggal Mulai</p>
                        <p>{{ $kelompokProgramKerja->tanggal_mulai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Tanggal Selesai</p>
                        <p>{{ $kelompokProgramKerja->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Luaran Stats -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Luaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Total</span>
                        <span class="font-semibold">{{ $statistikLuaran['total'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Selesai</span>
                        <span class="font-semibold">{{ $statistikLuaran['selesai'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Dosen Monev -->
            @if ($dosenMonev)
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Dosen Pemonev</h3>
                    <p class="text-gray-700 font-semibold">{{ $dosenMonev->dosen->nama }}</p>
                    <p class="text-sm text-gray-600">{{ $dosenMonev->dosen->nidn }}</p>
                    @if ($dosenMonev->nilai)
                        <div class="mt-3 pt-3 border-t border-blue-200">
                            <p class="text-sm text-gray-600">Nilai:</p>
                            <p class="text-lg font-semibold text-blue-600">{{ $dosenMonev->nilai }}</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-gray-100 rounded-lg p-6 text-center">
                    <p class="text-gray-600 text-sm">Belum ada dosen pemonev yang ditugaskan</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleForm(id) {
    document.getElementById(id).classList.toggle('hidden');
}
</script>
@endsection
