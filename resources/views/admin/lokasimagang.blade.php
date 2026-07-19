@extends('layouts.admin')

@section('title', 'Master Data Lokasi Magang')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div></div>
        <a href="{{ route('lokasimagang.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-100 group">
            <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
            Tambah Instansi Magang
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="magangTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Nama Instansi</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Alamat</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kontak</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Pendaftar / Maks</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($lokasiMagangs as $lokasi)
                    @php $jml = $lokasi->jumlahPendaftar(); $maks = $lokasi->maks_peserta; $penuh = $lokasi->isFull(); @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black border border-indigo-100 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-briefcase text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-800 tracking-tight">{{ $lokasi->nama_instansi }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-500 font-medium">
                            {{ $lokasi->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-slate-600">{{ $lokasi->kontak ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($maks)
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-24 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $penuh ? 'bg-red-500' : 'bg-indigo-500' }}" style="width: {{ min(100, ($jml / $maks) * 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-black {{ $penuh ? 'text-red-600' : 'text-slate-600' }}">{{ $jml }}/{{ $maks }}</span>
                                    @if($penuh)<span class="px-1.5 py-0.5 bg-red-50 text-red-600 text-[10px] font-black rounded border border-red-100">Penuh</span>@endif
                                </div>
                            @else
                                <span class="text-xs text-slate-400">{{ $jml }} pendaftar &bull; <span class="italic">tak terbatas</span></span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(Auth::guard('web')->user()?->isSuperAdmin())
                                <button type="button" onclick="openKapasitasModal('{{ route('lokasimagang.kapasitas', $lokasi->id) }}', {{ $maks ?? 'null' }}, '{{ addslashes($lokasi->nama_instansi) }}')"
                                    class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg hover:bg-indigo-600 hover:text-white transition-all flex items-center gap-1">
                                    <i class="fas fa-users-cog text-[10px]"></i> Kuota
                                </button>
                                @endif
                                <form action="{{ route('lokasimagang.delete', $lokasi->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-transparent hover:border-red-100" onclick="return confirm('Hapus instansi ini?')">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Atur Kuota -->
<div id="kapasitasModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-800"><i class="fas fa-users-cog text-indigo-500 mr-2"></i>Atur Kuota Instansi</h3>
            <button onclick="closeKapasitasModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400"><i class="fas fa-times"></i></button>
        </div>
        <form id="kapasitasForm" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <p id="kapasitasLabel" class="text-sm text-gray-500 font-medium"></p>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Maks Peserta <span class="text-gray-300 normal-case font-normal">(kosongkan = tak terbatas)</span></label>
                <input type="number" id="kapasitasInput" name="maks_peserta" min="1" max="9999" placeholder="Contoh: 20"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition-colors">Simpan</button>
                <button type="button" onclick="closeKapasitasModal()" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openKapasitasModal(action, maks, label) {
    document.getElementById('kapasitasForm').action = action;
    document.getElementById('kapasitasLabel').textContent = label;
    document.getElementById('kapasitasInput').value = maks !== null ? maks : '';
    const modal = document.getElementById('kapasitasModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeKapasitasModal() {
    const modal = document.getElementById('kapasitasModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.getElementById('kapasitasModal').addEventListener('click', function(e) {
    if (e.target === this) closeKapasitasModal();
});

$(document).ready(function() {
    $('#magangTable').DataTable({
        "language": {
            "search": "",
            "searchPlaceholder": "Cari instansi...",
            "lengthMenu": "_MENU_ baris"
        },
        "dom": '<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 gap-4"lf>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 gap-4"ip>'
    });
});
</script>
@endsection
