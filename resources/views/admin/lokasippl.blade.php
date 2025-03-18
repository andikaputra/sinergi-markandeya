@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Daftar Tempat PPL</h2>
    <a href="{{ route('lokasippl.create') }}" class="btn btn-primary mb-3">Tambah Sekolah</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sekolah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Lokasippl as $lokasippl)
                <tr>
                    <td>{{ $lokasippl->Sekolah }}</td>
                    <td>
                        <a href="" class="btn btn-warning">Edit</a>
                        <form action="{{ route('lokasippl.delete', $lokasippl->id) }}" method="POST" class="d-inline">
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
