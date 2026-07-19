@extends('layouts.pembimbing_luar')

@section('title', 'Mahasiswa Bimbingan')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 block">Tahun Akademik</label>
                <select name="tahun_akademik" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua</option>
                    @foreach($tahunAkademiks as $ta)
                        <option value="{{ $ta->tahun }} {{ $ta->semester }}" {{ $selectedTA == $ta->tahun.' '.$ta->semester ? 'selected' : '' }}>
                            {{ $ta->tahun }} {{ $ta->semester }} {{ $ta->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 block">Kegiatan</label>
                <select name="kegiatan" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua</option>
                    <option value="KKN" {{ $selectedKegiatan == 'KKN' ? 'selected' : '' }}>KKN</option>
                    <option value="PPL" {{ $selectedKegiatan == 'PPL' ? 'selected' : '' }}>PPL</option>
                    <option value="PKL" {{ $selectedKegiatan == 'PKL' ? 'selected' : '' }}>PKL</option>
                    <option value="Magang" {{ $selectedKegiatan == 'Magang' ? 'selected' : '' }}>Magang</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
        </form>
    </div>

    <!-- Daftar Mahasiswa -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="bimbinganTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Mahasiswa</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kegiatan</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Lokasi</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Nilai</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($mahasiswaBimbingan as $bimbingan)
                    @php $mhs = $bimbingan->mahasiswa; @endphp
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs border border-emerald-100">
                                    {{ substr($mhs->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $mhs->nama }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $mhs->nim }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg border border-blue-100">{{ $mhs->kegiatan }}</span>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-600">
                            @if($mhs->kegiatan == 'KKN')
                                {{ $mhs->penempatankkn?->lokasikkn?->desa ?? '-' }}
                            @elseif($mhs->kegiatan == 'PPL')
                                {{ $mhs->penempatanppl?->lokasippl?->Sekolah ?? '-' }}
                            @elseif($mhs->kegiatan == 'PKL')
                                {{ $mhs->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                            @elseif($mhs->kegiatan == 'Magang')
                                {{ $mhs->penempatanmagang?->lokasimagang?->nama_instansi ?? '-' }}
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            @if($bimbingan->nilai !== null)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-sm font-bold rounded-lg border border-emerald-100">{{ $bimbingan->nilai }}</span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-lg border border-amber-100">Belum Dinilai</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('pembimbing_luar.mahasiswa.detail', $mhs->nim) }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-600 font-bold text-xs rounded-xl hover:bg-emerald-600 hover:text-white transition-all border border-emerald-100">
                                <i class="fas fa-eye mr-1.5"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#bimbinganTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari mahasiswa..."
            }
        });
    });
</script>
@endsection
