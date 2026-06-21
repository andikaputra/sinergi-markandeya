@extends('layouts.admin')
@section('title', 'Pengumuman')
@section('content')
<div class="space-y-8">

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center gap-2">
        <i class="fas fa-check-circle"></i>{{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Form Buat Pengumuman --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="p-6 border-b border-gray-50 bg-slate-50/50">
                    <h3 class="text-lg font-black text-gray-800">Buat Pengumuman</h3>
                    <p class="text-xs text-gray-400 mt-1">Tulis dan publikasikan pengumuman ke mahasiswa.</p>
                </div>
                <form action="{{ route('pengumuman.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Judul Pengumuman</label>
                        <input type="text" name="judul" required placeholder="Contoh: Pembekalan KKN Angkatan 2026"
                            class="block w-full px-4 py-3 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Target Penerima</label>
                        <select name="target" required class="block w-full px-4 py-3 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-bold appearance-none">
                            <option value="semua">Semua Mahasiswa</option>
                            <option value="KKN">KKN saja</option>
                            <option value="PPL">PPL saja</option>
                            <option value="PKL">PKL saja</option>
                            <option value="Magang">Magang saja</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Isi Pengumuman</label>
                        <textarea name="isi" rows="5" required placeholder="Tulis isi pengumuman di sini..."
                            class="block w-full px-4 py-3 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-100 transition-all text-sm uppercase tracking-widest">
                        <i class="fas fa-save mr-2"></i> Simpan Draft
                    </button>
                </form>
            </div>
        </div>

        {{-- Daftar Pengumuman --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-800">Daftar Pengumuman</h3>
                <span class="text-xs text-gray-400">{{ $pengumuman->total() }} pengumuman</span>
            </div>

            @forelse($pengumuman as $p)
            <div class="bg-white rounded-[2rem] border {{ $p->is_published ? 'border-emerald-100' : 'border-gray-100' }} overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                @if($p->is_published)
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase border border-emerald-100">
                                    <i class="fas fa-circle text-[6px] mr-1 animate-pulse"></i>Dipublikasi
                                </span>
                                @else
                                <span class="px-2.5 py-1 bg-gray-50 text-gray-400 rounded-lg text-[10px] font-black uppercase">Draft</span>
                                @endif
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase">
                                    {{ $p->target === 'semua' ? 'Semua' : $p->target }}
                                </span>
                            </div>
                            <h4 class="font-black text-gray-800 text-base">{{ $p->judul }}</h4>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $p->is_published ? 'Dipublikasi ' . $p->published_at->format('d/m/Y H:i') : 'Dibuat ' . $p->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">{{ $p->isi }}</p>

                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-50 flex-wrap">
                        @if(!$p->is_published)
                        <form action="{{ route('pengumuman.publish', $p->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all">
                                <i class="fas fa-paper-plane mr-1"></i> Publikasikan
                            </button>
                        </form>
                        @else
                        <form action="{{ route('pengumuman.unpublish', $p->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-amber-50 text-amber-600 hover:bg-amber-100 text-xs font-bold rounded-xl transition-all">
                                <i class="fas fa-eye-slash mr-1"></i> Sembunyikan
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus pengumuman ini?')"
                                class="px-4 py-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white text-xs font-bold rounded-xl transition-all">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[2rem] border border-gray-100 p-12 text-center">
                <i class="fas fa-bullhorn text-3xl text-gray-200 mb-3 block"></i>
                <p class="text-gray-400 font-medium">Belum ada pengumuman. Buat yang pertama!</p>
            </div>
            @endforelse

            {{ $pengumuman->links() }}
        </div>
    </div>
</div>
@endsection
