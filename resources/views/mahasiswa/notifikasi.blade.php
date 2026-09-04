@extends('layouts.adminmhs')

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header Banner -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider">
                    Pusat Informasi
                </span>
                <span class="text-xs text-gray-400">• Realtime Updates</span>
            </div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight mt-1">Notifikasi Mahasiswa</h2>
            <p class="text-sm text-gray-500">Pemberitahuan resmi terkait review bimbingan, verifikasi berkas, dan pengumuman kegiatan.</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
            <i class="fas fa-bell"></i>
        </div>
    </div>

    <!-- Notification List -->
    <div class="space-y-4">
        @forelse($notifikasis as $n)
        @php
            $config = match($n->tipe) {
                'sukses'    => [
                    'bg' => 'bg-white hover:bg-emerald-50/40',
                    'border' => 'border-gray-100 hover:border-emerald-200',
                    'icon_bg' => 'bg-emerald-100 text-emerald-600',
                    'icon' => 'fa-check-circle',
                    'badge' => 'bg-emerald-100 text-emerald-700',
                    'label' => 'Sukses'
                ],
                'peringatan'=> [
                    'bg' => 'bg-white hover:bg-amber-50/40',
                    'border' => 'border-gray-100 hover:border-amber-200',
                    'icon_bg' => 'bg-amber-100 text-amber-600',
                    'icon' => 'fa-exclamation-circle',
                    'badge' => 'bg-amber-100 text-amber-700',
                    'label' => 'Perhatian'
                ],
                default     => [
                    'bg' => 'bg-white hover:bg-blue-50/40',
                    'border' => 'border-gray-100 hover:border-blue-200',
                    'icon_bg' => 'bg-blue-100 text-blue-600',
                    'icon' => 'fa-info-circle',
                    'badge' => 'bg-blue-100 text-blue-700',
                    'label' => 'Info'
                ],
            };
        @endphp
        <div class="{{ $config['bg'] }} rounded-3xl border {{ $config['border'] }} p-6 shadow-sm transition-all duration-200 flex items-start gap-4">
            <div class="w-11 h-11 rounded-2xl {{ $config['icon_bg'] }} flex items-center justify-center text-lg shrink-0 font-bold">
                <i class="fas {{ $config['icon'] }}"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 text-[10px] font-black rounded-lg uppercase tracking-wider {{ $config['badge'] }}">
                            {{ $config['label'] }}
                        </span>
                        <h4 class="font-black text-gray-800 text-sm sm:text-base">{{ $n->judul }}</h4>
                    </div>
                    <span class="text-[11px] font-bold text-gray-400">
                        <i class="far fa-clock mr-1"></i> {{ $n->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed mt-1.5">{{ $n->isi }}</p>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl border border-gray-100 p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h3 class="text-base font-bold text-gray-700">Belum Ada Notifikasi</h3>
            <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">Notifikasi baru akan otomatis muncul saat dosen mereview bimbingan atau admin merilis pengumuman baru.</p>
        </div>
        @endforelse

        @if ($notifikasis->hasPages())
            <div class="pt-4">
                {{ $notifikasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
