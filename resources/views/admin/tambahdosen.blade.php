@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Tambah Dosen</h2>

    <form action="{{ route('dosen.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>NIDN:</label>
            <input type="text" name="nidn" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection