@extends('layouts.admin')

@section('title', 'Master Data Lokasi PKL')

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

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div></div>
        <a href="{{ route('lokasipkl.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-amber-100 group">
            <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
            Tambah Instansi
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="pklTable">
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
                    @foreach ($lokasiPkls as $lokasi)
                    @php $jml = $lokasi->jumlahPendaftar(); $maks = $lokasi->maks_peserta; $penuh = $lokasi->isFull(); @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black border border-amber-100 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-building text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-gray-800 tracking-tight">{{ $lokasi->nama_instansi }}</span>
                                    @if($lokasi->website)
                                    <p class="text-[10px] text-amber-600 font-medium mt-0.5"><i class="fas fa-globe mr-1"></i>{{ $lokasi->website }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-500 font-medium">
                            {{ $lokasi->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col space-y-1">
                                <span class="text-xs font-bold text-slate-600">{{ $lokasi->kontak ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $lokasi->email ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($maks)
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-24 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $penuh ? 'bg-red-500' : 'bg-amber-500' }}" style="width: {{ min(100, ($jml / $maks) * 100) }}%"></div>
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
                                <button type="button" onclick='openEditLokasiPklModal(@json($lokasi))'
                                    class="px-3 py-1.5 bg-amber-50 text-amber-600 text-xs font-bold rounded-lg hover:bg-amber-500 hover:text-white transition-all flex items-center gap-1">
                                    <i class="fas fa-edit text-[10px]"></i> Edit
                                </button>
                                @if(Auth::guard('web')->user()?->isSuperAdmin())
                                <button type="button" onclick="openKapasitasModal('{{ route('lokasipkl.kapasitas', $lokasi->id) }}', {{ $maks ?? 'null' }}, '{{ addslashes($lokasi->nama_instansi) }}')"
                                    class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg hover:bg-indigo-600 hover:text-white transition-all flex items-center gap-1">
                                    <i class="fas fa-users-cog text-[10px]"></i> Kuota
                                </button>
                                @endif
                                <form action="{{ route('lokasipkl.delete', $lokasi->id) }}" method="POST" class="inline">
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

<!-- Modal Edit Lokasi PKL -->
<div id="editLokasiPklModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-lg shadow-amber-100">
                    <i class="fas fa-edit text-sm"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-800">Edit Instansi PKL</h3>
                    <p class="text-xs text-gray-400 font-medium">Perbarui data instansi atau mitra PKL</p>
                </div>
            </div>
            <button onclick="closeEditLokasiPklModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editLokasiPklForm" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label for="edit_pkl_nama_instansi" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Perusahaan / Instansi <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-300">
                        <i class="fas fa-industry text-xs"></i>
                    </div>
                    <input type="text" id="edit_pkl_nama_instansi" name="nama_instansi" required placeholder="Contoh: PT. Teknologi Indonesia"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="edit_pkl_alamat" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Kantor</label>
                <div class="relative">
                    <div class="absolute top-3.5 left-0 pl-3.5 flex items-center pointer-events-none text-gray-300">
                        <i class="fas fa-map-marker-alt text-xs"></i>
                    </div>
                    <textarea id="edit_pkl_alamat" name="alamat" rows="3" placeholder="Jl. Raya Utama No. 123..."
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="edit_pkl_kontak" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Telepon / WhatsApp</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-300">
                            <i class="fas fa-phone text-xs"></i>
                        </div>
                        <input type="text" id="edit_pkl_kontak" name="kontak" placeholder="0812..."
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label for="edit_pkl_email" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Email Instansi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-300">
                            <i class="fas fa-envelope text-xs"></i>
                        </div>
                        <input type="email" id="edit_pkl_email" name="email" placeholder="hrd@company.com"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="edit_pkl_website" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Website Instansi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-300">
                        <i class="fas fa-globe text-xs"></i>
                    </div>
                    <input type="text" id="edit_pkl_website" name="website" placeholder="https://company.com"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="pt-3 flex gap-3">
                <button type="submit" class="flex-1 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-amber-100 flex items-center justify-center gap-2">
                    <i class="fas fa-save text-xs"></i> Simpan Perubahan
                </button>
                <button type="button" onclick="closeEditLokasiPklModal()" class="px-5 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl text-sm transition-colors">
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
function openEditLokasiPklModal(data) {
    const form = document.getElementById('editLokasiPklForm');
    form.action = `/lokasipkl/${data.id}`;
    document.getElementById('edit_pkl_nama_instansi').value = data.nama_instansi || '';
    document.getElementById('edit_pkl_alamat').value = data.alamat || '';
    document.getElementById('edit_pkl_kontak').value = data.kontak || '';
    document.getElementById('edit_pkl_email').value = data.email || '';
    document.getElementById('edit_pkl_website').value = data.website || '';

    const modal = document.getElementById('editLokasiPklModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditLokasiPklModal() {
    const modal = document.getElementById('editLokasiPklModal');
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

document.getElementById('editLokasiPklModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditLokasiPklModal();
});

document.getElementById('kapasitasModal').addEventListener('click', function(e) {
    if (e.target === this) closeKapasitasModal();
});

$(document).ready(function() {
    $('#pklTable').DataTable({
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
