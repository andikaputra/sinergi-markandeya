<td class="px-4 py-5">
    @if($mahasiswa->dosenPembimbing?->dosen)
        <div class="flex items-center space-x-1.5 mb-1">
            <i class="fas fa-user-tie text-[8px] text-blue-400"></i>
            <span class="text-[11px] font-bold text-gray-700">{{ $mahasiswa->dosenPembimbing->dosen->nama }}</span>
        </div>
    @else
        <span class="text-[10px] text-gray-300 italic">- Belum ada -</span>
    @endif
</td>
<td class="px-4 py-5">
    @if($mahasiswa->dosenPenguji?->dosen)
        <div class="flex items-center space-x-1.5 mb-1">
            <i class="fas fa-gavel text-[8px] text-indigo-400"></i>
            <span class="text-[11px] font-bold text-gray-700">{{ $mahasiswa->dosenPenguji->dosen->nama }}</span>
        </div>
    @else
        <span class="text-[10px] text-gray-300 italic">- Belum ada -</span>
    @endif
</td>
<td class="px-4 py-5">
    @if($mahasiswa->pembimbingLuarMahasiswa?->pembimbingLuar)
        <div class="flex items-center space-x-1.5 mb-1">
            <i class="fas fa-user-friends text-[8px] text-emerald-400"></i>
            <span class="text-[11px] font-bold text-gray-700">{{ $mahasiswa->pembimbingLuarMahasiswa->pembimbingLuar->nama }}</span>
        </div>
    @else
        <span class="text-[10px] text-gray-300 italic">- Belum ada -</span>
    @endif
</td>
<td class="px-4 py-5">
    @if($mahasiswa->dosenPenilaiPublikasi?->dosen)
        <div class="flex items-center space-x-1.5 mb-1">
            <i class="fas fa-book-reader text-[8px] text-purple-400"></i>
            <span class="text-[11px] font-bold text-gray-700">{{ $mahasiswa->dosenPenilaiPublikasi->dosen->nama }}</span>
        </div>
    @else
        <span class="text-[10px] text-gray-300 italic">- Belum ada -</span>
    @endif
</td>
