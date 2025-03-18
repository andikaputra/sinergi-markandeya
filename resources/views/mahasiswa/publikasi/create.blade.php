@extends('layouts.adminmhs')

@section('content')
<div class="container">
    <h2>Upload Publikasi</h2>
    <form action="{{ route('publikasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Judul:</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="form-group">
            <label>link:</label>
            <input type="text" name="link" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Upload</button>
    </form>
</div>
@endsection
