@extends('layouts.adminmhs')

@section('title', 'Tambah Jurnal')

@section('content')
<div class="container">
    <h2>Tambah Jurnal Harian</h2>
    <form action="{{ route('jurnal.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="kegiatan">Kegiatan:</label>
            <textarea name="kegiatan" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
