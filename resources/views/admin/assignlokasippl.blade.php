@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Penempatan PPL</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('assign.lokasippl.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label>Pilih Mahasiswa</label>
                <div class="border p-2" style="max-height: 300px; overflow-y: scroll;">
                    @foreach($mahasiswas as $mahasiswa)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="nims[]" value="{{ $mahasiswa->nim }}" id="mahasiswa{{ $mahasiswa->nim }}">
                            <label class="form-check-label" for="mahasiswa{{ $mahasiswa->nim }}">
                                {{ $mahasiswa->nama }} ({{ $mahasiswa->prodi }}) ({{ $mahasiswa->kecamatan }})
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <label>Pilih Sekolah PPL</label>
                <select name="sekolah" class="form-control" required>
                    <option value="">Pilih Lokasi</option>
                    @foreach($lokasippls as $lokasippl)
                        <option value="{{ $lokasippl->id }}">{{ $lokasippl->Sekolah }} </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary mt-4">Assign</button>
            </div>
        </div>
    </form>

    <h3 class="mt-4">Daftar Lokasi PPL Mahasiswa</h3>
    <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Mahasiswa</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignmentslokasippl as $assignment)
            <tr>
                <td>{{ $assignment->nim }}</td>
                <td>{{ $assignment->mahasiswa->nama }} </td>
                <td>{{ $assignment->lokasippl->Sekolah }}</td>
                <td>
                    <form action="{{ route('assign.lokasippl.delete', $assignment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
