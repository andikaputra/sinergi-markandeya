<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerja;
use App\Models\Luaran;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenProgramKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:dosen');
    }

    private function getMahasiswaBimbingan()
    {
        $dosen = Auth::guard('dosen')->user();
        return Mahasiswa::whereIn('nim', function ($query) use ($dosen) {
            $query->select('nim')
                ->from('dosen_pembimbings')
                ->where('nidn', $dosen->nidn);
        })->pluck('nim');
    }

    public function dashboard()
    {
        $mahasiswaBimbingan = $this->getMahasiswaBimbingan();

        $totalProgram = ProgramKerja::whereIn('nim', $mahasiswaBimbingan)->count();
        $totalMahasiswa = $mahasiswaBimbingan->count();
        $mahasiswaDenganProgram = ProgramKerja::whereIn('nim', $mahasiswaBimbingan)
            ->distinct('nim')
            ->count('nim');
        $mahasiswaTanpaProgram = $totalMahasiswa - $mahasiswaDenganProgram;

        $statistikStatus = [
            'rencana' => ProgramKerja::whereIn('nim', $mahasiswaBimbingan)
                ->where('status', 'rencana')->count(),
            'sedang_berjalan' => ProgramKerja::whereIn('nim', $mahasiswaBimbingan)
                ->where('status', 'sedang_berjalan')->count(),
            'selesai' => ProgramKerja::whereIn('nim', $mahasiswaBimbingan)
                ->where('status', 'selesai')->count(),
        ];

        $recentPrograms = ProgramKerja::whereIn('nim', $mahasiswaBimbingan)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dosen.program-kerja.dashboard', compact(
            'totalProgram',
            'totalMahasiswa',
            'mahasiswaDenganProgram',
            'mahasiswaTanpaProgram',
            'statistikStatus',
            'recentPrograms'
        ));
    }

    public function mahasiswaBimbingan()
    {
        $mahasiswaBimbinganNim = $this->getMahasiswaBimbingan();
        $mahasiswaBimbingan = Mahasiswa::whereIn('nim', $mahasiswaBimbinganNim)
            ->orderBy('nama', 'asc')
            ->paginate(20);

        return view('dosen.program-kerja.mahasiswa-bimbingan', compact('mahasiswaBimbingan'));
    }

    public function detailMahasiswa(Mahasiswa $mahasiswa)
    {
        $dosen = Auth::guard('dosen')->user();

        $isBimbinganDosen = \App\Models\DosenPembimbing::where('nidn', $dosen->nidn)
            ->where('nim', $mahasiswa->nim)
            ->exists();

        if (!$isBimbinganDosen) {
            abort(403, 'Anda tidak berwenang mengakses data mahasiswa ini');
        }

        $programs = ProgramKerja::where('nim', $mahasiswa->nim)
            ->orderBy('created_at', 'desc')
            ->get();

        $luarans = Luaran::whereIn('program_kerja_id', $programs->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dosen.program-kerja.detail-mahasiswa', compact('mahasiswa', 'programs', 'luarans'));
    }

    public function semuaProgram()
    {
        $mahasiswaBimbingan = $this->getMahasiswaBimbingan();
        $programs = ProgramKerja::whereIn('nim', $mahasiswaBimbingan)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dosen.program-kerja.semua-program', compact('programs'));
    }

    public function semuaLuaran()
    {
        $mahasiswaBimbingan = $this->getMahasiswaBimbingan();
        $luarans = Luaran::whereIn('program_kerja_id', function ($query) use ($mahasiswaBimbingan) {
            $query->select('id')
                ->from('program_kerjas')
                ->whereIn('nim', $mahasiswaBimbingan);
        })
        ->with('programKerja.mahasiswa')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('dosen.program-kerja.semua-luaran', compact('luarans'));
    }
}
