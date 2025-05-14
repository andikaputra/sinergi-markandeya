@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Ajukan Lokasi PKL</h2>

    <div class="card p-4">
        <form action="{{ route('pengajuanpkl.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nama_instansi" class="form-label">Nama Instansi</label>
                <input type="text" name="nama_instansi" class="form-control" required>
                @error('nama_instansi')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Instansi</label>
                <textarea name="alamat" class="form-control" rows="3" required></textarea>
                @error('alamat')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kontak" class="form-label">Kontak Instansi (Opsional)</label>
                <input type="text" name="kontak" class="form-control">
                @error('kontak')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Ajukan</button>
        </form>
    </div>
</div>
@endsection
