@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Daftar Tempat KKN</h2>
    <a href="{{ route('lokasikkn.create') }}" class="btn btn-primary mb-3">Tambah Lokai KKN</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>desa</th>
                <th>Alamat</th>
                <th>Kecamatan</th>
                <th>Kabupaten</th>
                <th>Provinsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tempatKKNs as $tempatKKN)
                <tr>
                    <td>{{ $tempatKKN->desa }}</td>
                    <td>{{ $tempatKKN->alamat }}</td>
                    <td>{{ $tempatKKN->kecamatan }}</td>
                    <td>{{ $tempatKKN->kabupaten }}</td>
                    <td>{{ $tempatKKN->provinsi }}</td>
                    <td>
                        <a href="" class="btn btn-warning">Edit</a>
                        <form action="{{ route('lokasikkn.delete', $tempatKKN->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
