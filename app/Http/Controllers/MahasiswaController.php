<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Penempatankkn;
use App\Models\Penempatanppl;
use App\Models\PenempatanPkl;
use App\Models\PenempatanMagang;
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
            'kegiatan' => 'required|in:KKN,PPL,PKL,Magang',
            'kecamatan' => 'required|string|max:255',
            'prodi' => 'required|in:PGSD,PBSI,PBI,SI,ME,PARBUD,HUKUM',
            'pembayaranKRS' => 'required|string|max:255',
            'KRS' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mahasiswas',
            'password' => 'required|string|min:8|confirmed',
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
            'password' => $request->password, // hashed by model cast
            'tahun_akademik' => $taString
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function showDashboard()
    {
        $mahasiswa = Mahasiswa::with(['dosenPembimbing.dosen', 'dosenPenguji.dosen'])
            ->where('nim', Auth::user()->nim)->first();
    
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

        $penempatanmagangmhs = PenempatanMagang::with(['mahasiswa', 'lokasimagang'])
            ->where('nim', Auth::user()->nim)
            ->first();

        // Ambil teman satu lokasi
        $temanSeLokasi = collect();
        $nim = Auth::user()->nim;

        if ($penempatankknmhs) {
            $temanSeLokasi = Penempatankkn::with('mahasiswa')
                ->where('lokasi_kkn_id', $penempatankknmhs->lokasi_kkn_id)
                ->where('nim', '!=', $nim)
                ->get()->pluck('mahasiswa');
        } elseif ($penempatanpplmhs) {
            $temanSeLokasi = Penempatanppl::with('mahasiswa')
                ->where('sekolah_id', $penempatanpplmhs->sekolah_id)
                ->where('nim', '!=', $nim)
                ->get()->pluck('mahasiswa');
        } elseif ($penempatanpklmhs) {
            $temanSeLokasi = PenempatanPkl::with('mahasiswa')
                ->where('lokasi_pkl_id', $penempatanpklmhs->lokasi_pkl_id)
                ->where('nim', '!=', $nim)
                ->get()->pluck('mahasiswa');
        } elseif ($penempatanmagangmhs) {
            $temanSeLokasi = PenempatanMagang::with('mahasiswa')
                ->where('lokasi_magang_id', $penempatanmagangmhs->lokasi_magang_id)
                ->where('nim', '!=', $nim)
                ->get()->pluck('mahasiswa');
        }

        return view('mahasiswa.dashboard', compact('penempatankknmhs', 'penempatanpplmhs', 'penempatanpklmhs', 'penempatanmagangmhs', 'mahasiswa', 'temanSeLokasi'));
    }

    public function temanSeLokasi()
    {
        $nim = Auth::user()->nim;
        $kegiatan = Auth::user()->kegiatan;
        $temanSeLokasi = collect();
        $namaLokasi = null;

        if ($kegiatan == 'KKN') {
            $penempatan = Penempatankkn::with('lokasikkn')->where('nim', $nim)->first();
            if ($penempatan) {
                $namaLokasi = 'Desa ' . $penempatan->lokasikkn->desa;
                $temanSeLokasi = Penempatankkn::with('mahasiswa')
                    ->where('lokasi_kkn_id', $penempatan->lokasi_kkn_id)
                    ->where('nim', '!=', $nim)
                    ->get()->pluck('mahasiswa');
            }
        } elseif ($kegiatan == 'PPL') {
            $penempatan = Penempatanppl::with('lokasippl')->where('nim', $nim)->first();
            if ($penempatan) {
                $namaLokasi = $penempatan->lokasippl->Sekolah;
                $temanSeLokasi = Penempatanppl::with('mahasiswa')
                    ->where('sekolah_id', $penempatan->sekolah_id)
                    ->where('nim', '!=', $nim)
                    ->get()->pluck('mahasiswa');
            }
        } elseif ($kegiatan == 'PKL') {
            $penempatan = PenempatanPkl::with('lokasipkl')->where('nim', $nim)->first();
            if ($penempatan) {
                $namaLokasi = $penempatan->lokasipkl->nama_instansi;
                $temanSeLokasi = PenempatanPkl::with('mahasiswa')
                    ->where('lokasi_pkl_id', $penempatan->lokasi_pkl_id)
                    ->where('nim', '!=', $nim)
                    ->get()->pluck('mahasiswa');
            }
        } elseif ($kegiatan == 'Magang') {
            $penempatan = PenempatanMagang::with('lokasimagang')->where('nim', $nim)->first();
            if ($penempatan) {
                $namaLokasi = $penempatan->lokasimagang->nama_instansi;
                $temanSeLokasi = PenempatanMagang::with('mahasiswa')
                    ->where('lokasi_magang_id', $penempatan->lokasi_magang_id)
                    ->where('nim', '!=', $nim)
                    ->get()->pluck('mahasiswa');
            }
        }

        return view('mahasiswa.teman_selokasi', compact('temanSeLokasi', 'namaLokasi', 'kegiatan'));
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
