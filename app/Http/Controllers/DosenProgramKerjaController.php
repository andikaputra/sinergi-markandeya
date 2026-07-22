<?php

namespace App\Http\Controllers;

use App\Models\IndividuProgramKerja;
use App\Models\KelompokProgramKerja;
use App\Models\IndividuLuaran;
use App\Models\KelompokLuaran;
use App\Models\DosenMonev;
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
        $dosen = Auth::guard('dosen')->user();
        $mahasiswaBimbinganNim = $this->getMahasiswaBimbingan();

        $totalProposalIndividu = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->count();
        $totalProposalKelompok = KelompokProgramKerja::where('kategori', '!=', '')->count();
        $totalMonevPrograms = DosenMonev::where('nidn', $dosen->nidn)->count();

        $statistikIndividu = [
            'rencana' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'rencana')->count(),
            'sedang_berjalan' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'sedang_berjalan')->count(),
            'selesai' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'selesai')->count(),
        ];

        $recentPrograms = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $monevPrograms = DosenMonev::where('nidn', $dosen->nidn)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dosen.program-kerja.dashboard', compact(
            'totalProposalIndividu',
            'totalProposalKelompok',
            'totalMonevPrograms',
            'statistikIndividu',
            'recentPrograms',
            'monevPrograms'
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

        $individuPrograms = IndividuProgramKerja::where('nim', $mahasiswa->nim)
            ->orderBy('created_at', 'desc')
            ->get();

        $individuLuarans = IndividuLuaran::whereIn('individu_program_kerja_id', $individuPrograms->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dosen.program-kerja.detail-mahasiswa', compact('mahasiswa', 'individuPrograms', 'individuLuarans'));
    }

    public function semuaProgram()
    {
        $mahasiswaBimbingan = $this->getMahasiswaBimbingan();
        $programs = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbingan)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dosen.program-kerja.semua-program', compact('programs'));
    }

    public function semuaLuaran()
    {
        $mahasiswaBimbingan = $this->getMahasiswaBimbingan();
        $luarans = IndividuLuaran::whereIn('individu_program_kerja_id', function ($query) use ($mahasiswaBimbingan) {
            $query->select('id')
                ->from('individu_program_kerjas')
                ->whereIn('nim', $mahasiswaBimbingan);
        })
        ->with('programKerja.mahasiswa')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('dosen.program-kerja.semua-luaran', compact('luarans'));
    }

    // Dosen Monev Methods
    public function monevDashboard()
    {
        $dosen = Auth::guard('dosen')->user();
        $monevPrograms = DosenMonev::where('nidn', $dosen->nidn)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dosen.program-kerja.monev-dashboard', compact('monevPrograms'));
    }

    public function monevDetail($type, $programId)
    {
        $dosen = Auth::guard('dosen')->user();

        $monev = DosenMonev::where('nidn', $dosen->nidn)
            ->where('monev_type', $type)
            ->where('program_id', $programId)
            ->firstOrFail();

        if ($type === 'individu') {
            $program = IndividuProgramKerja::findOrFail($programId);
            $luarans = $program->luarans;
            return view('dosen.program-kerja.monev-detail', compact('program', 'monev', 'type', 'luarans'));
        } else {
            $program = KelompokProgramKerja::findOrFail($programId);
            $anggota = $program->anggota();
            $luarans = $program->luarans;
            return view('dosen.program-kerja.monev-detail', compact('program', 'monev', 'type', 'anggota', 'luarans'));
        }
    }

    public function inputNilaiMonev(Request $request, $type, $programId)
    {
        $dosen = Auth::guard('dosen')->user();

        $monev = DosenMonev::where('nidn', $dosen->nidn)
            ->where('monev_type', $type)
            ->where('program_id', $programId)
            ->firstOrFail();

        $validated = $request->validate([
            'nilai' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $monev->update($validated);

        return back()->with('success', 'Nilai dan catatan monev berhasil disimpan');
    }
}
