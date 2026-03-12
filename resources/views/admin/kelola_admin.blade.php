@extends('layouts.admin')

@section('title', 'Kelola Admin')

@section('content')
<div class="space-y-8">
    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-5 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm font-bold flex items-center">
        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i>
        {{ session('error') }}
    </div>
    @endif


    <!-- Tambah Admin Form -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-user-plus text-blue-500 mr-3"></i>
            Tambah Admin Baru
        </h4>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl">
            <ul class="text-red-600 text-sm font-medium space-y-1">
                @foreach($errors->all() as $error)
                <li><i class="fas fa-times-circle mr-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.kelola.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600"
                        placeholder="Nama admin">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600"
                        placeholder="admin@markandeya.ac.id">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Password</label>
                    <input type="password" name="password" required minlength="8"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600"
                        placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Role</label>
                    <select name="role" id="roleSelect" onchange="toggleKegiatan()"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Terbatas per Kegiatan)</option>
                        <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin (Akses Penuh)</option>
                    </select>
                </div>
            </div>

            <!-- Kegiatan Checkboxes -->
            <div id="kegiatanSection" class="mb-6">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Kegiatan yang Dikelola</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach(['KKN', 'PPL', 'PKL', 'Magang'] as $k)
                    <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:border-blue-300 hover:bg-blue-50/50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="checkbox" name="kegiatan[]" value="{{ $k }}"
                            {{ is_array(old('kegiatan')) && in_array($k, old('kegiatan')) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-3 text-sm font-bold text-gray-700">{{ $k }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-2">Pilih kegiatan yang boleh diakses oleh admin ini.</p>
            </div>

            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-blue-200">
                <i class="fas fa-plus mr-2"></i>Tambah Admin
            </button>
        </form>
    </div>

    <!-- Daftar Admin -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50">
            <h4 class="text-lg font-bold text-gray-800">Daftar Admin Terdaftar</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">No</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Admin</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Role</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Akses Kegiatan</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($admins as $i => $admin)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-5 text-sm text-gray-400 font-bold">{{ $i + 1 }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl {{ $admin->isSuperAdmin() ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center font-bold text-xs">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $admin->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $admin->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @if($admin->isSuperAdmin())
                                <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-black border border-amber-200">
                                    <i class="fas fa-crown mr-1"></i> Super Admin
                                </span>
                            @else
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-black border border-blue-200">
                                    Admin
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            @if($admin->isSuperAdmin())
                                <span class="text-xs font-bold text-amber-600">Semua Kegiatan</span>
                            @elseif(is_array($admin->kegiatan) && count($admin->kegiatan) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($admin->kegiatan as $k)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] font-black uppercase">{{ $k }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum diatur</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right">
                            @if($admin->id !== Auth::id())
                                <form action="{{ route('admin.kelola.delete', $admin->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus admin {{ $admin->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-red-500 hover:bg-red-50 hover:border-red-200 transition-all">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Anda</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleKegiatan() {
    const role = document.getElementById('roleSelect').value;
    const section = document.getElementById('kegiatanSection');
    section.style.display = role === 'superadmin' ? 'none' : 'block';
}
toggleKegiatan();
</script>
@endsection
