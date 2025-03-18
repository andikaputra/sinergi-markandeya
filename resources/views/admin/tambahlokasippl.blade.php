@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Tambah Tempat PPL</h2>
    <form action="{{ route('lokasippl.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Sekolah:</label>
            <input type="text" name="Sekolah" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
