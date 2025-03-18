@extends('layouts.adminmhs')

@section('title', 'Jurnal Harian')

@section('content')
<div class="container">
    <h2>Jurnal Harian</h2>
    <a href="{{ route('jurnal.create') }}" class="btn btn-primary mb-3">Tambah Jurnal</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jurnals as $jurnal)
                <tr>
                    <td>{{ $jurnal->tanggal }}</td>
                    <td>{{ $jurnal->kegiatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
