<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\MahasiswaKegiatan;
use App\Models\PenempatanKkn;
use App\Models\PenempatanPpl;
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:mahasiswas',
            'kampus' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'prodi' => 'required|in:PGSD,PBSI,PBI,SI,ME,PARBUD,HUKUM',
            'pembayaranKRS' => 'required|string|max:255',
            'KRS' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mahasiswas',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan data ke database (tanpa kegiatan - dipilih setelah login)
        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'kampus' => $request->kampus,
            'kecamatan' => $request->kecamatan,
            'prodi' => $request->prodi,
            'pembayaranKRS' => $request->pembayaranKRS,
            'KRS' => $request->KRS,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login dan pilih kegiatan.');
    }

    public function showDashboard()
    {
        $mahasiswa = Mahasiswa::with([
            'dosenPembimbing.dosen', 'dosenPenguji.dosen',
            'pembimbingLuarMahasiswa.pembimbingLuar', 'activeKegiatan',
            'mahasiswaKegiatan'
        ])->where('nim', Auth::user()->nim)->first();

        // Cek apakah sudah punya kegiatan aktif
        $hasActiveKegiatan = $mahasiswa->activeKegiatan !== null;
        $riwayatKegiatan = $mahasiswa->mahasiswaKegiatan;
        $activeTA = TahunAkademik::active();
        $taString = $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null;

        // Ambil lokasi penempatan
        $penempatankknmhs = PenempatanKkn::with(['mahasiswa', 'lokasikkn'])
            ->where('nim', Auth::user()->nim)->first();

        $penempatanpplmhs = PenempatanPpl::with(['mahasiswa', 'lokasippl'])
            ->where('nim', Auth::user()->nim)->first();

        $penempatanpklmhs = PenempatanPkl::with(['mahasiswa', 'lokasipkl'])
            ->where('nim', Auth::user()->nim)->first();

        $penempatanmagangmhs = PenempatanMagang::with(['mahasiswa', 'lokasimagang'])
            ->where('nim', Auth::user()->nim)->first();

        // Ambil teman satu lokasi
        $temanSeLokasi = collect();
        $nim = Auth::user()->nim;

        if ($penempatankknmhs) {
            $temanSeLokasi = PenempatanKkn::with('mahasiswa')
                ->where('lokasi_kkn_id', $penempatankknmhs->lokasi_kkn_id)
                ->where('nim', '!=', $nim)
                ->get()->pluck('mahasiswa');
        } elseif ($penempatanpplmhs) {
            $temanSeLokasi = PenempatanPpl::with('mahasiswa')
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

        return view('mahasiswa.dashboard', compact(
            'penempatankknmhs', 'penempatanpplmhs', 'penempatanpklmhs',
            'penempatanmagangmhs', 'mahasiswa', 'temanSeLokasi',
            'hasActiveKegiatan', 'riwayatKegiatan', 'taString'
        ));
    }

    /**
     * Daftar kegiatan baru (setelah login)
     */
    public function daftarKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required|in:KKN,PPL,PKL,Magang',
        ]);

        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->firstOrFail();
        $activeTA = TahunAkademik::active();
        $taString = $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null;

        // Cek apakah sudah punya kegiatan yang sama di TA yang sama
        $exists = MahasiswaKegiatan::where('nim', $mahasiswa->nim)
            ->where('kegiatan', $request->kegiatan)
            ->where('tahun_akademik', $taString)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di kegiatan ini untuk tahun akademik ini.');
        }

        // Tambah kegiatan baru (otomatis nonaktifkan yang lama)
        $mahasiswa->addKegiatan($request->kegiatan, $taString);

        // Dual-write: update kolom lama juga
        $mahasiswa->update([
            'kegiatan' => $request->kegiatan,
            'tahun_akademik' => $taString,
        ]);

        return redirect()->route('dashboard')->with('success', 'Berhasil mendaftar kegiatan ' . $request->kegiatan . '!');
    }

    /**
     * Ganti kegiatan aktif (untuk mahasiswa yang punya beberapa kegiatan)
     */
    public function switchKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:mahasiswa_kegiatan,id',
        ]);

        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->firstOrFail();

        // Pastikan kegiatan ini milik mahasiswa yang login
        $kegiatan = MahasiswaKegiatan::where('id', $request->kegiatan_id)
            ->where('nim', $mahasiswa->nim)
            ->firstOrFail();

        // Nonaktifkan semua, aktifkan yang dipilih
        $mahasiswa->mahasiswaKegiatan()->update(['is_active' => false]);
        $kegiatan->update(['is_active' => true]);

        // Dual-write
        $mahasiswa->update([
            'kegiatan' => $kegiatan->kegiatan,
            'tahun_akademik' => $kegiatan->tahun_akademik,
        ]);

        return redirect()->route('dashboard')->with('success', 'Berhasil beralih ke kegiatan ' . $kegiatan->kegiatan . '.');
    }

    public function temanSeLokasi()
    {
        $nim = Auth::user()->nim;
        $kegiatan = Auth::user()->kegiatan;
        $temanSeLokasi = collect();
        $namaLokasi = null;

        if ($kegiatan == 'KKN') {
            $penempatan = PenempatanKkn::with('lokasikkn')->where('nim', $nim)->first();
            if ($penempatan) {
                $namaLokasi = 'Desa ' . $penempatan->lokasikkn->desa;
                $temanSeLokasi = PenempatanKkn::with('mahasiswa')
                    ->where('lokasi_kkn_id', $penempatan->lokasi_kkn_id)
                    ->where('nim', '!=', $nim)
                    ->get()->pluck('mahasiswa');
            }
        } elseif ($kegiatan == 'PPL') {
            $penempatan = PenempatanPpl::with('lokasippl')->where('nim', $nim)->first();
            if ($penempatan) {
                $namaLokasi = $penempatan->lokasippl->Sekolah;
                $temanSeLokasi = PenempatanPpl::with('mahasiswa')
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
