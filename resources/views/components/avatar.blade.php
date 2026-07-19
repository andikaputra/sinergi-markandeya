{{--
    Avatar component — tampilkan foto jika ada, fallback ke inisial nama.
    Props:
      - foto  : URL foto (nullable)
      - nama  : nama untuk inisial & alt text
      - size  : class ukuran, default "w-full h-full"
      - shape : "circle" | "rounded" (default circle)
--}}
@props(['foto' => null, 'nama' => 'U', 'size' => 'w-full h-full', 'shape' => 'circle'])

@php
    $rounded = $shape === 'circle' ? 'rounded-full' : 'rounded-2xl';
    $inisial = strtoupper(substr(trim($nama), 0, 1) ?: 'U');
@endphp

@if ($foto)
    <img src="{{ $foto }}"
         alt="{{ $nama }}"
         class="{{ $size }} {{ $rounded }} object-cover"
         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    <div class="{{ $size }} {{ $rounded }} bg-blue-50 items-center justify-center text-blue-600 font-black text-2xl hidden">
        {{ $inisial }}
    </div>
@else
    <div class="{{ $size }} {{ $rounded }} bg-blue-50 flex items-center justify-center text-blue-600 font-black text-2xl">
        {{ $inisial }}
    </div>
@endif
