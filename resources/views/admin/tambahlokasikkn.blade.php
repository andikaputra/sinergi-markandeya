@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Tambah Tempat KKN</h2>
    <form action="{{ route('lokasikkn.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Tempat:</label>
            <input type="text" name="desa" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alamat:</label>
            <input type="text" name="alamat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Kecamatan:</label>
            <input type="text" name="kecamatan" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Kabupaten:</label>
            <input type="text" name="kabupaten" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Provinsi:</label>
            <input type="text" name="provinsi" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
