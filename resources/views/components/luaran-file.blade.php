@props(['luaran'])
@php
    $path = $luaran->file_path;
    $external = is_string($path) && filter_var($path, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($path, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true);
    $local = is_string($path) && $path !== '' && !str_contains($path, '://') && !str_starts_with($path, '/') && !str_contains($path, '\\') && !in_array('..', explode('/', $path), true);
    $type = $luaran instanceof \App\Models\KelompokLuaran ? 'kelompok' : 'individu';
@endphp
@if ($external || $local)
<a class="dash-link inline-flex items-center gap-1 mt-1 text-xs" href="{{ $external ? $path : route('luaran.berkas', ['type'=>$type, 'id'=>$luaran->id]) }}" @if($external) target="_blank" rel="noopener noreferrer" @endif>
    <i class="fas {{ $external ? 'fa-external-link-alt' : 'fa-download' }}" aria-hidden="true"></i>
    {{ $external ? 'Buka / unduh berkas' : 'Unduh berkas' }}
</a>
@else
<small class="block text-gray-500">Berkas belum ditambahkan</small>
@endif
