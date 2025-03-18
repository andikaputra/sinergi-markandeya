@extends('layouts.adminmhs')

@section('content')
<div class="container">
    <h2>Daftar Publikasi</h2>
    <a href="{{ route('publikasi.create') }}" class="btn btn-primary mb-3">Tambah Publikasi</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Link</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($publikasis as $publikasi)
                <tr>
                    <td>{{ $publikasi->judul }}</td>
                    <td><a href="{{  $publikasi->link }}" class="btn btn-success" target="_blank">Buka</a></td>
                    <td>
                        <form action="{{ route('publikasi.destroy', $publikasi->id) }}" method="POST" class="d-inline">
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
