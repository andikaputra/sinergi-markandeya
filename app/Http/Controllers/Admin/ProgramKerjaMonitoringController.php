<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use App\Models\Luaran;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class ProgramKerjaMonitoringController extends Controller
{
    public function dashboard()
    {
        $totalProgram = ProgramKerja::count();
        $totalMahasiswa = Mahasiswa::count();
        $mahasiswaDenganProgram = ProgramKerja::distinct('nim')->count('nim');
        $mahasiswaTanpaProgram = $totalMahasiswa - $mahasiswaDenganProgram;

        $statistikStatus = [
            'rencana' => ProgramKerja::where('status', 'rencana')->count(),
            'sedang_berjalan' => ProgramKerja::where('status', 'sedang_berjalan')->count(),
            'selesai' => ProgramKerja::where('status', 'selesai')->count(),
            'tunda' => ProgramKerja::where('status', 'tunda')->count(),
        ];

        $recentPrograms = ProgramKerja::with('mahasiswa')
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
        $mahasiswaTanpaProgram = Mahasiswa::whereNotIn('nim', ProgramKerja::distinct('nim')->pluck('nim'))
            ->orderBy('nama', 'asc')
            ->paginate(20);

        return view('admin.program-kerja.mahasiswa-tanpa-program', compact('mahasiswaTanpaProgram'));
    }

    public function detailMahasiswa(Mahasiswa $mahasiswa)
    {
        $programs = ProgramKerja::where('nim', $mahasiswa->nim)
            ->orderBy('created_at', 'desc')
            ->get();

        $luarans = Luaran::whereIn('program_kerja_id', $programs->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.program-kerja.detail-mahasiswa', compact('mahasiswa', 'programs', 'luarans'));
    }

    public function semuaProgram()
    {
        $programs = ProgramKerja::with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.program-kerja.semua-program', compact('programs'));
    }

    public function semuaLuaran()
    {
        $luarans = Luaran::with('programKerja.mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.program-kerja.semua-luaran', compact('luarans'));
    }
}
