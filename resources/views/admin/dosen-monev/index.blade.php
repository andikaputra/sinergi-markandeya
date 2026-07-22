@extends('layouts.adminmhs')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800">Plotting Dosen Pemonev (Monitoring & Evaluasi)</h1>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="card mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $kegiatan === 'kkn' ? 'active' : '' }}" href="?kegiatan=kkn&type={{ $type }}">KKN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $kegiatan === 'ppl' ? 'active' : '' }}" href="?kegiatan=ppl&type={{ $type }}">PPL</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $kegiatan === 'pkl' ? 'active' : '' }}" href="?kegiatan=pkl&type={{ $type }}">PKL</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $kegiatan === 'magang' ? 'active' : '' }}" href="?kegiatan=magang&type={{ $type }}">Magang</a>
                </li>
            </ul>

            <div class="mt-3">
                <div class="btn-group" role="group">
                    <a href="?kegiatan={{ $kegiatan }}&type=individu" class="btn btn-sm {{ $type === 'individu' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Program Individu
                    </a>
                    <a href="?kegiatan={{ $kegiatan }}&type=kelompok" class="btn btn-sm {{ $type === 'kelompok' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Program Kelompok
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Program List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Program {{ ucfirst($type) }} - {{ strtoupper($kegiatan) }}</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if ($programs->count() > 0)
                        <form id="assignForm" action="{{ route('admin.dosen-monev.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="monev_type" value="{{ $type }}">

                            <div class="mb-3">
                                <label class="form-label"><strong>Pilih Dosen Pemonev</strong></label>
                                <select name="nidn" id="dosenSelect" class="form-select @error('nidn') is-invalid @enderror" required>
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nidn }}">{{ $dosen->nama }} ({{ $dosen->nidn }})</option>
                                    @endforeach
                                </select>
                                @error('nidn')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Pilih Program yang Akan Dimonev</strong></label>
                                <div class="list-group">
                                    @foreach ($programs as $program)
                                        <div class="list-group-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="program_ids[]" value="{{ $program->id }}" id="prog_{{ $program->id }}">
                                                <label class="form-check-label w-100" for="prog_{{ $program->id }}">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong>{{ $program->judul }}</strong>
                                                            @if ($type === 'individu')
                                                                <br>
                                                                <small class="text-muted">{{ $program->mahasiswa->nama ?? '-' }} ({{ $program->nim }})</small>
                                                            @else
                                                                <br>
                                                                <small class="text-muted">Ketua: {{ $program->mahasiswaKetua->nama ?? '-' }} ({{ $program->nim_ketua }})</small>
                                                            @endif
                                                        </div>
                                                        @if (isset($existingAssignments) && in_array($program->id, $existingAssignments))
                                                            @if ($program->dosenMonev)
                                                                <span class="badge bg-success">{{ $program->dosenMonev->dosen->nama }}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Tugaskan Dosen Pemonev
                            </button>
                        </form>
                    @else
                        <p class="text-muted text-center py-5">Tidak ada program {{ $type }} untuk {{ $kegiatan }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Existing Assignments -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Penugasan Saat Ini</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @php
                        $assignments = \App\Models\DosenMonev::where('monev_type', $type)->get();
                    @endphp

                    @if ($assignments->count() > 0)
                        <div class="list-group">
                            @foreach ($assignments as $assignment)
                                @php
                                    if ($type === 'individu') {
                                        $program = \App\Models\IndividuProgramKerja::find($assignment->program_id);
                                    } else {
                                        $program = \App\Models\KelompokProgramKerja::find($assignment->program_id);
                                    }
                                @endphp
                                @if ($program)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <strong class="text-truncate">{{ $program->judul }}</strong>
                                            <br>
                                            <small class="text-muted">Dosen: {{ $assignment->dosen->nama }}</small>
                                            <br>
                                            <small class="text-muted">Nilai: <span class="badge bg-secondary">{{ $assignment->nilai ?? 'Belum dinilai' }}</span></small>
                                        </div>
                                        <form action="{{ route('admin.dosen-monev.delete', $assignment->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-5">Tidak ada penugasan</p>
                    @endif
                </div>
            </div>

            <!-- Import CSV -->
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Import CSV</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.dosen-monev.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">File CSV</label>
                            <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
                            <small class="text-muted">Format: NIM, NIDN</small>
                        </div>
                        <input type="hidden" name="monev_type" value="{{ $type }}">
                        <button type="submit" class="btn btn-warning btn-sm w-100">
                            <i class="fas fa-upload"></i> Import
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
