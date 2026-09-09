<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndividuProgramKerja;
use App\Models\IndividuLuaran;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class ProgramKerjaMonitoringController extends Controller
{
    public function dashboard()
    {
        $totalProgram = IndividuProgramKerja::count();
        $totalMahasiswa = Mahasiswa::count();
        $mahasiswaDenganProgram = IndividuProgramKerja::distinct('nim')->count('nim');
        $mahasiswaTanpaProgram = max(0, $totalMahasiswa - $mahasiswaDenganProgram);

        $statistikStatus = [
            'rencana' => IndividuProgramKerja::where('status', 'rencana')->count(),
            'sedang_berjalan' => IndividuProgramKerja::where('status', 'sedang_berjalan')->count(),
            'selesai' => IndividuProgramKerja::where('status', 'selesai')->count(),
            'tunda' => IndividuProgramKerja::where('status', 'tunda')->count(),
        ];

        $recentPrograms = IndividuProgramKerja::with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.program-kerja.dashboard', compact(
            'totalProgram',
            'totalMahasiswa',
            'mahasiswaDenganProgram',
            'mahasiswaTanpaProgram',
            'statistikStatus',
            'recentPrograms'
        ));
    }

    public function mahasiswaTanpaProgram()
    {
        $mahasiswaTanpaProgram = Mahasiswa::whereNotIn('nim', IndividuProgramKerja::distinct('nim')->pluck('nim'))
            ->orderBy('nama', 'asc')
            ->paginate(20);

        return view('admin.program-kerja.mahasiswa-tanpa-program', compact('mahasiswaTanpaProgram'));
    }

    public function detailMahasiswa($mahasiswa)
    {
        if ($mahasiswa instanceof Mahasiswa) {
            $mhs = $mahasiswa;
        } else {
            $mhs = Mahasiswa::where('nim', $mahasiswa)->orWhere('id', $mahasiswa)->firstOrFail();
        }

        $programs = IndividuProgramKerja::where('nim', $mhs->nim)
            ->with(['luarans', 'dosenMonev.dosen'])
            ->orderBy('created_at', 'desc')
            ->get();

        $luarans = IndividuLuaran::whereIn('individu_program_kerja_id', $programs->pluck('id'))
            ->with('programKerja')
            ->orderBy('created_at', 'desc')
            ->get();

        $mahasiswa = $mhs;

        return view('admin.program-kerja.detail-mahasiswa', compact('mahasiswa', 'programs', 'luarans'));
    }

    public function semuaProgram()
    {
        $programs = IndividuProgramKerja::with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.program-kerja.semua-program', compact('programs'));
    }

    public function semuaLuaran()
    {
        $luarans = IndividuLuaran::with('programKerja.mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.program-kerja.semua-luaran', compact('luarans'));
    }
}
