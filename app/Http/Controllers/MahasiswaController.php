<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Penempatankkn;
use App\Models\Penempatanppl;
use App\Models\PenempatanPkl;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class MahasiswaController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:mahasiswas',
            'kampus' => 'required|string|max:255',
            'kegiatan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'pembayaranKRS' => 'required|string|max:255',
            'KRS' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mahasiswas',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Ambil Tahun Akademik Aktif
        $activeTA = TahunAkademik::active();
        $taString = $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null;

        // Simpan data ke database
        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'kampus' => $request->kampus,
            'kegiatan' => $request->kegiatan,
            'kecamatan' => $request->kecamatan,
            'prodi' => $request->prodi,
            'pembayaranKRS' => $request->pembayaranKRS,
            'KRS' => $request->KRS,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tahun_akademik' => $taString
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function showDashboard()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->first();
    
        // Ambil lokasi KKN, PPL, atau PKL berdasarkan NIM yang login
        $penempatankknmhs = Penempatankkn::with(['mahasiswa', 'lokasikkn'])
            ->where('nim', Auth::user()->nim)
            ->first();
    
        $penempatanpplmhs = Penempatanppl::with(['mahasiswa', 'lokasippl'])
            ->where('nim', Auth::user()->nim)
            ->first();

        $penempatanpklmhs = PenempatanPkl::with(['mahasiswa', 'lokasipkl'])
            ->where('nim', Auth::user()->nim)
            ->first();
    
        return view('mahasiswa.dashboard', compact('penempatankknmhs', 'penempatanpplmhs', 'penempatanpklmhs', 'mahasiswa'));
    }

    public function saveLaporan(Request $request)
    {
        $request->validate([
            'laporan_link' => 'required|url'
        ]);

        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->firstOrFail();
        $mahasiswa->update([
            'laporan_link' => $request->laporan_link
        ]);

        return redirect()->back()->with('success', 'Link laporan akhir berhasil disimpan!');
    }
}
