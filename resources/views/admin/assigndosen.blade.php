@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Assign Dosen Pembimbing</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('assign.dosen.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label>Pilih Mahasiswa</label>
                <div class="border p-2" style="max-height: 300px; overflow-y: scroll;">
                    @foreach($mahasiswas as $mahasiswa)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="nims[]" value="{{ $mahasiswa->nim }}" id="mahasiswa{{ $mahasiswa->nim }}">
                            <label class="form-check-label" for="mahasiswa{{ $mahasiswa->nim }}">
                                {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <label>Pilih Dosen</label>
                <select name="nidn" class="form-control" required>
                    <option value="">Pilih Dosen</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->nidn }}">{{ $dosen->nama }} ({{ $dosen->nidn }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary mt-4">Assign</button>
            </div>
        </div>
    </form>

    <h3 class="mt-4">Daftar Dosen Pembimbing</h3>
    <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>Mahasiswa</th>
                <th>Dosen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
            <tr>
                <td>{{ $assignment->mahasiswa->nama }} ({{ $assignment->nim }})</td>
                <td>{{ $assignment->dosen->nama }} ({{ $assignment->nidn }})</td>
                <td>
                    <form action="{{ route('assign.dosen.delete', $assignment->id) }}" method="POST">
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
