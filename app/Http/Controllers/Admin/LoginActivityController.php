<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $thirtyDaysAgo = now()->subDays(30);
        $sevenDaysAgo = now()->subDays(7);

        // Mahasiswa stats
        $totalMahasiswa = Mahasiswa::count();
        $mahasiswaSudahLogin = Mahasiswa::whereNotNull('last_login')->count();
        $mahasiswaBelumLogin = $totalMahasiswa - $mahasiswaSudahLogin;
        $mahasiswaTidakLoginSebulan = Mahasiswa::where('last_login', '<', $thirtyDaysAgo)
            ->orWhereNull('last_login')
            ->count();

        // Dosen stats
        $totalDosen = Dosen::count();
        $dosenSudahLogin = Dosen::whereNotNull('last_login')->count();
        $dosenBelumLogin = $totalDosen - $dosenSudahLogin;
        $dosenTidakLoginSebulan = Dosen::where('last_login', '<', $thirtyDaysAgo)
            ->orWhereNull('last_login')
            ->count();

        $statistik = [
            'mahasiswa' => [
                'total' => $totalMahasiswa,
                'sudah_login' => $mahasiswaSudahLogin,
                'belum_login' => $mahasiswaBelumLogin,
                'tidak_login_sebulan' => $mahasiswaTidakLoginSebulan,
                'persentase' => $totalMahasiswa > 0 ? round(($mahasiswaSudahLogin / $totalMahasiswa) * 100, 1) : 0,
            ],
            'dosen' => [
                'total' => $totalDosen,
                'sudah_login' => $dosenSudahLogin,
                'belum_login' => $dosenBelumLogin,
                'tidak_login_sebulan' => $dosenTidakLoginSebulan,
                'persentase' => $totalDosen > 0 ? round(($dosenSudahLogin / $totalDosen) * 100, 1) : 0,
            ],
        ];

        // Data untuk chart
        $mahasiswaTerbaru = Mahasiswa::orderBy('last_login', 'desc')
            ->whereNotNull('last_login')
            ->limit(5)
            ->get();

        $dosenTerbaru = Dosen::orderBy('last_login', 'desc')
            ->whereNotNull('last_login')
            ->limit(5)
            ->get();

        return view('admin.login-activity.dashboard', compact('statistik', 'mahasiswaTerbaru', 'dosenTerbaru'));
    }

    public function mahasiswaBelumLogin()
    {
        $mahasiswa = Mahasiswa::whereNull('last_login')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('admin.login-activity.mahasiswa-belum-login', compact('mahasiswa'));
    }

    public function mahasiswaTidakAktif()
    {
        $thirtyDaysAgo = now()->subDays(30);

        $mahasiswa = Mahasiswa::where(function ($query) use ($thirtyDaysAgo) {
            $query->where('last_login', '<', $thirtyDaysAgo)
                ->orWhereNull('last_login');
        })
        ->orderBy('last_login', 'asc')
        ->paginate(25);

        return view('admin.login-activity.mahasiswa-tidak-aktif', compact('mahasiswa'));
    }

    public function dosenBelumLogin()
    {
        $dosen = Dosen::whereNull('last_login')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('admin.login-activity.dosen-belum-login', compact('dosen'));
    }

    public function dosenTidakAktif()
    {
        $thirtyDaysAgo = now()->subDays(30);

        $dosen = Dosen::where(function ($query) use ($thirtyDaysAgo) {
            $query->where('last_login', '<', $thirtyDaysAgo)
                ->orWhereNull('last_login');
        })
        ->orderBy('last_login', 'asc')
        ->paginate(25);

        return view('admin.login-activity.dosen-tidak-aktif', compact('dosen'));
    }

    public function aktivitasLogin()
    {
        $mahasiswas = Mahasiswa::whereNotNull('last_login')
            ->orderBy('last_login', 'desc')
            ->paginate(20);

        $dosens = Dosen::whereNotNull('last_login')
            ->orderBy('last_login', 'desc')
            ->paginate(20);

        return view('admin.login-activity.aktivitas-login', compact('mahasiswas', 'dosens'));
    }
}
