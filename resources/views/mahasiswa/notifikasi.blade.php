@extends('layouts.adminmhs')
@section('title', 'Notifikasi')
@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-black text-gray-800">Notifikasi</h2>
        <span class="text-xs text-gray-400">{{ $notifikasis->total() }} notifikasi</span>
    </div>

    @forelse($notifikasis as $n)
    @php
        $colors = match($n->tipe) {
            'sukses'    => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-100', 'icon' => 'fa-check-circle text-emerald-500', 'dot' => 'bg-emerald-400'],
            'peringatan'=> ['bg' => 'bg-amber-50',   'border' => 'border-amber-100',   'icon' => 'fa-exclamation-circle text-amber-500', 'dot' => 'bg-amber-400'],
            default     => ['bg' => 'bg-blue-50',    'border' => 'border-blue-100',    'icon' => 'fa-info-circle text-blue-500', 'dot' => 'bg-blue-400'],
        };
    @endphp
    <div class="{{ $colors['bg'] }} rounded-2xl border {{ $colors['border'] }} p-5 flex gap-4">
        <div class="shrink-0 mt-0.5">
            <i class="fas {{ $colors['icon'] }} text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-gray-800 text-sm">{{ $n->judul }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ $n->isi }}</p>
            <p class="text-[10px] text-gray-400 mt-2">{{ $n->created_at->diffForHumans() }}</p>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-[2.5rem] border border-gray-100 p-16 text-center">
        <i class="fas fa-bell-slash text-4xl text-gray-200 mb-4 block"></i>
        <p class="text-gray-400 font-medium">Belum ada notifikasi</p>
    </div>
    @endforelse

    {{ $notifikasis->links() }}
</div>
@endsection
