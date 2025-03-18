@extends('layouts.adminmhs')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="row">
    <!-- Profil Mahasiswa -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Profil Mahasiswa</h4>
                <p><strong>Nama:</strong> {{ Auth::user()->nama }}</p>
                <p><strong>NIM:</strong> {{ Auth::user()->nim }}</p>
                <p><strong>Kampus:</strong> {{ Auth::user()->kampus }}</p>
                <p><strong>Program Studi:</strong> {{ Auth::user()->prodi_full }}</p>
            </div>
        </div>
    </div>

    <!-- Kegiatan Mahasiswa -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Kegiatan</h4>
                <p><strong>Jenis Kegiatan:</strong> {{ Auth::user()->kegiatan }}</p>
                @if(Auth::user()->kegiatan  == 'KKN')
                    @if ($penempatankknmhs)
                    <p><strong>Penempatan:</strong> {{ $penempatankknmhs->lokasikkn->desa }}</p>
                    @else
                    <p><strong>Penempatan:</strong> Belum ada lokasi KKN yang ditetapkan.</p>
                    @endif
                @elseif (Auth::user()->kegiatan  == 'PPL')
                @if ($penempatanpplmhs)
                    <p><strong>Sekolah:</strong> {{ $penempatanpplmhs->lokasippl->Sekolah }}</p>
                    @else
                    <p><strong>Sekolah:</strong> Belum ada lokasi PPL yang ditetapkan.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
