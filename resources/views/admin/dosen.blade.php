@extends('layouts.admin')

@section('title', 'Data Dosen')

@section('content')
<div class="container">
    <h2>Dosen Pembimbing</h2>
    <a href="{{ route('dosen.create') }}" class="btn btn-primary mb-3">Tambah Dosen</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIDN</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dosens as $dosen)
                <tr>
                    <td>{{ $dosen->nidn }}</td>
                    <td>{{ $dosen->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
