@extends('layouts.admin')

@section('title', 'Master Data Lokasi KKN')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm font-bold flex items-center">
        <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div></div>
        <a href="{{ route('lokasikkn.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-100 group">
            <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform"></i>
            Tambah Lokasi Baru
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="lokasiTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Wilayah Desa</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kecamatan</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Pendaftar / Maks</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tempatKKNs as $tempatKKN)
                    @php $jml = $tempatKKN->jumlahPendaftar(); $maks = $tempatKKN->maks_peserta; $penuh = $tempatKKN->isFull(); @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold border border-blue-100 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-map-marked-alt text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-700">Desa {{ $tempatKKN->desa }}</span>
                                    @if($tempatKKN->alamat)
                                    <p class="text-xs text-slate-400 font-normal mt-0.5"><i class="fas fa-map-pin text-[10px] mr-1 text-slate-300"></i>{{ $tempatKKN->alamat }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-slate-600 px-3 py-1 bg-slate-100 rounded-full border border-slate-200">{{ $tempatKKN->kecamatan ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($maks)
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-24 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $penuh ? 'bg-red-500' : 'bg-blue-500' }}" style="width: {{ min(100, ($jml / $maks) * 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-black {{ $penuh ? 'text-red-600' : 'text-slate-600' }}">{{ $jml }}/{{ $maks }}</span>
                                    @if($penuh)<span class="px-1.5 py-0.5 bg-red-50 text-red-600 text-[10px] font-black rounded border border-red-100">Penuh</span>@endif
                                </div>
                            @else
                                <span class="text-xs text-slate-400">{{ $jml }} pendaftar &bull; <span class="italic">tak terbatas</span></span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick='openEditLokasiKknModal(@json($tempatKKN))'
                                    class="px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-600 hover:text-white transition-all flex items-center gap-1">
                                    <i class="fas fa-edit text-[10px]"></i> Edit
                                </button>
                                @if(Auth::guard('web')->user()?->isSuperAdmin())
                                <button type="button" onclick="openKapasitasModal('{{ route('lokasikkn.kapasitas', $tempatKKN->id) }}', {{ $maks ?? 'null' }}, 'Desa {{ addslashes($tempatKKN->desa) }}')"
                                    class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg hover:bg-indigo-600 hover:text-white transition-all flex items-center gap-1">
                                    <i class="fas fa-users-cog text-[10px]"></i> Kuota
                                </button>
                                @endif
                                <form action="{{ route('lokasikkn.delete', $tempatKKN->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all" onclick="return confirm('Hapus lokasi ini?')">
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

<!-- Modal Edit Lokasi KKN -->
<div id="editLokasiKknModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-lg shadow-blue-100">
                    <i class="fas fa-edit text-sm"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-800">Edit Lokasi KKN</h3>
                    <p class="text-xs text-gray-400 font-medium">Perbarui informasi tempat pengabdian KKN</p>
                </div>
            </div>
            <button onclick="closeEditLokasiKknModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editLokasiKknForm" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label for="edit_desa" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Desa / Wilayah <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-300">
                        <i class="fas fa-home text-xs"></i>
                    </div>
                    <input type="text" id="edit_desa" name="desa" required placeholder="Contoh: Desa Taro"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="edit_alamat" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-300">
                        <i class="fas fa-map-pin text-xs"></i>
                    </div>
                    <input type="text" id="edit_alamat" name="alamat" placeholder="Nama Jalan, Dusun, Banjar, dll."
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="edit_kecamatan" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Kecamatan</label>
                    <input type="text" id="edit_kecamatan" name="kecamatan" placeholder="Kecamatan"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all">
                </div>
                <div class="space-y-1.5">
                    <label for="edit_kabupaten" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Kabupaten</label>
                    <input type="text" id="edit_kabupaten" name="kabupaten" placeholder="Kabupaten"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="edit_provinsi" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Provinsi</label>
                <input type="text" id="edit_provinsi" name="provinsi" placeholder="Provinsi"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all">
            </div>

            <div class="pt-3 flex gap-3">
                <button type="submit" class="flex-1 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
                    <i class="fas fa-save text-xs"></i> Simpan Perubahan
                </button>
                <button type="button" onclick="closeEditLokasiKknModal()" class="px-5 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl text-sm transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Atur Kuota -->
<div id="kapasitasModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-800"><i class="fas fa-users-cog text-indigo-500 mr-2"></i>Atur Kuota Lokasi</h3>
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
function openEditLokasiKknModal(data) {
    const form = document.getElementById('editLokasiKknForm');
    form.action = `/lokasikkn/${data.id}`;
    document.getElementById('edit_desa').value = data.desa || '';
    document.getElementById('edit_alamat').value = data.alamat || '';
    document.getElementById('edit_kecamatan').value = data.kecamatan || '';
    document.getElementById('edit_kabupaten').value = data.kabupaten || '';
    document.getElementById('edit_provinsi').value = data.provinsi || '';

    const modal = document.getElementById('editLokasiKknModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditLokasiKknModal() {
    const modal = document.getElementById('editLokasiKknModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

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

document.getElementById('editLokasiKknModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditLokasiKknModal();
});

document.getElementById('kapasitasModal').addEventListener('click', function(e) {
    if (e.target === this) closeKapasitasModal();
});

$(document).ready(function() {
    $('#lokasiTable').DataTable({
        "language": { "search": "", "searchPlaceholder": "Cari lokasi...", "lengthMenu": "_MENU_ baris" },
        "dom": '<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 gap-4"lf>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 gap-4"ip>'
    });
});
</script>
@endsection
