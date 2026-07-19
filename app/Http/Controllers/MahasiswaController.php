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
use App\Models\Pengumuman;
use App\Models\LokasiKkn;
use App\Models\LokasiPpl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\AisService;


class MahasiswaController extends Controller
{
    public function __construct(private AisService $ais) {}

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
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu verifikasi admin sebelum bisa login.');
    }

    public function showDashboard(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        // Sync profile from AIS once per login session
        if ($mahasiswa->ais_token && !$request->session()->get('ais_profil_synced_mhs')) {
            $mahasiswa = $this->ais->syncMahasiswaFromProfil($mahasiswa);
            $request->session()->put('ais_profil_synced_mhs', true);
        }

        $mahasiswa = Mahasiswa::with([
            'dosenPembimbing.dosen', 'dosenPenguji.dosen',
            'pembimbingLuarMahasiswa.pembimbingLuar', 'activeKegiatan',
            'mahasiswaKegiatan'
        ])->where('nim', $mahasiswa->nim)->first();

        // Cek apakah sudah punya kegiatan aktif
        $hasActiveKegiatan = $mahasiswa->activeKegiatan !== null;
        $riwayatKegiatan = $mahasiswa->mahasiswaKegiatan;
        $activeTA = TahunAkademik::active();
        $taString = $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null;

        // Semua tahun akademik yang sedang buka pendaftaran
        $openTahunAkademiks = TahunAkademik::whereNotNull('tanggal_mulai_daftar')
            ->whereNotNull('tanggal_selesai_daftar')
            ->where('tanggal_mulai_daftar', '<=', now()->toDateString())
            ->where('tanggal_selesai_daftar', '>=', now()->toDateString())
            ->orderBy('tahun', 'desc')
            ->get();

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

        $kegiatan = $mahasiswa->kegiatan;
        $pengumumanList = Pengumuman::published()
            ->untukKegiatan($kegiatan)
            ->limit(5)
            ->get();

        return view('mahasiswa.dashboard', compact(
            'penempatankknmhs', 'penempatanpplmhs', 'penempatanpklmhs',
            'penempatanmagangmhs', 'mahasiswa', 'temanSeLokasi',
            'hasActiveKegiatan', 'riwayatKegiatan', 'taString',
            'openTahunAkademiks', 'pengumumanList'
        ));
    }

    /**
     * Daftar kegiatan baru (setelah login)
     */
    public function daftarKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan'              => 'required|in:KKN,PPL,PKL,Magang',
            'tahun_akademik_id'     => 'required|exists:tahun_akademiks,id',
            'preferensi_lokasi_id'  => 'nullable|integer',
            'nama_instansi_pilihan' => 'nullable|string|max:255',
            'alamat_instansi'       => 'nullable|string|max:500',
            'bidang_minat'          => 'nullable|string|max:255',
            'skill'                 => 'nullable|string|max:500',
            'motivasi'              => 'nullable|string|max:1000',
            'link_dokumen_1'        => 'required|url|max:500',
            'link_dokumen_2'        => 'required|url|max:500',
            'link_dokumen_3'        => in_array($request->kegiatan, ['PKL', 'Magang']) ? 'required|url|max:500' : 'nullable|url|max:500',
        ]);

        $ta = TahunAkademik::findOrFail($request->tahun_akademik_id);

        if (!$ta->isPendaftaranOpen()) {
            return redirect()->back()->with('error', 'Periode pendaftaran untuk tahun akademik ini sudah ditutup.');
        }

        // Cek kapasitas lokasi untuk KKN dan PPL
        if ($request->filled('preferensi_lokasi_id')) {
            if ($request->kegiatan === 'KKN') {
                $lokasi = \App\Models\LokasiKkn::find($request->preferensi_lokasi_id);
                if ($lokasi && $lokasi->isFull()) {
                    return redirect()->back()->with('error', 'Kuota lokasi Desa ' . $lokasi->desa . ' sudah penuh (' . $lokasi->maks_peserta . ' orang). Silakan pilih lokasi lain.');
                }
            } elseif ($request->kegiatan === 'PPL') {
                $lokasi = \App\Models\LokasiPpl::find($request->preferensi_lokasi_id);
                if ($lokasi && $lokasi->isFull()) {
                    return redirect()->back()->with('error', 'Kuota lokasi ' . $lokasi->Sekolah . ' sudah penuh (' . $lokasi->maks_peserta . ' orang). Silakan pilih lokasi lain.');
                }
            }
        }

        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->firstOrFail();
        $taString = $ta->tahun . ' ' . $ta->semester;

        // Cegah duplikat kegiatan yang sama di TA yang sama
        $exists = MahasiswaKegiatan::where('nim', $mahasiswa->nim)
            ->where('kegiatan', $request->kegiatan)
            ->where('tahun_akademik', $taString)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di kegiatan ' . $request->kegiatan . ' untuk tahun akademik ' . $taString . '.');
        }

        // KKN, PPL, PKL: hanya 1 per tahun akademik
        if (in_array($request->kegiatan, ['KKN', 'PPL', 'PKL'])) {
            $sudahAdaKkn = MahasiswaKegiatan::where('nim', $mahasiswa->nim)
                ->whereIn('kegiatan', ['KKN', 'PPL', 'PKL'])
                ->where('tahun_akademik', $taString)
                ->first();

            if ($sudahAdaKkn) {
                return redirect()->back()->with('error',
                    'Anda sudah terdaftar di ' . $sudahAdaKkn->kegiatan . ' untuk tahun akademik ' . $taString . '. KKN, PPL, dan PKL hanya boleh 1 per tahun akademik.');
            }
        }

        $mk = $mahasiswa->addKegiatan($request->kegiatan, $taString);

        $mk->update([
            'preferensi_lokasi_id'  => $request->preferensi_lokasi_id,
            'nama_instansi_pilihan' => $request->nama_instansi_pilihan,
            'alamat_instansi'       => $request->alamat_instansi,
            'bidang_minat'          => $request->bidang_minat,
            'skill'                 => $request->skill,
            'motivasi'              => $request->motivasi,
            'link_dokumen_1'        => $request->link_dokumen_1,
            'link_dokumen_2'        => $request->link_dokumen_2,
            'link_dokumen_3'        => $request->link_dokumen_3,
        ]);

        $mahasiswa->update([
            'kegiatan'      => $request->kegiatan,
            'tahun_akademik'=> $taString,
        ]);

        return redirect()->route('mahasiswa.daftar-kegiatan.page')
            ->with('success', 'Berhasil mendaftar ' . $request->kegiatan . ' untuk tahun akademik ' . $taString . '!');
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

    public function showDaftarKegiatan()
    {
        $mahasiswa = Mahasiswa::with('mahasiswaKegiatan')
            ->where('nim', Auth::user()->nim)->firstOrFail();

        $openTahunAkademiks = TahunAkademik::whereNotNull('tanggal_mulai_daftar')
            ->whereNotNull('tanggal_selesai_daftar')
            ->where('tanggal_mulai_daftar', '<=', now()->toDateString())
            ->where('tanggal_selesai_daftar', '>=', now()->toDateString())
            ->orderBy('tahun', 'desc')
            ->get();

        $lokasiKkn = LokasiKkn::orderBy('desa')->get();
        $lokasiPpl = LokasiPpl::orderBy('Sekolah')->get();

        return view('mahasiswa.daftar_kegiatan', compact(
            'mahasiswa', 'openTahunAkademiks', 'lokasiKkn', 'lokasiPpl'
        ));
    }

    public function batalkanKegiatan(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->firstOrFail();
        $mk = MahasiswaKegiatan::where('id', $id)->where('nim', $mahasiswa->nim)->firstOrFail();

        // Cek apakah sudah dapat penempatan
        $sudahPenempatan = match($mk->kegiatan) {
            'KKN'    => PenempatanKkn::where('nim', $mahasiswa->nim)->exists(),
            'PPL'    => PenempatanPpl::where('nim', $mahasiswa->nim)->exists(),
            'PKL'    => PenempatanPkl::where('nim', $mahasiswa->nim)->exists(),
            'Magang' => PenempatanMagang::where('nim', $mahasiswa->nim)->exists(),
            default  => false,
        };

        if ($sudahPenempatan) {
            return redirect()->back()->with('error', 'Pendaftaran ' . $mk->kegiatan . ' tidak bisa dibatalkan karena sudah mendapat penempatan lokasi.');
        }

        $mk->update(['status_kegiatan' => 'dibatalkan', 'is_active' => false]);

        // Jika ini kegiatan aktif, cari kegiatan lain untuk diaktifkan
        if ($mahasiswa->kegiatan === $mk->kegiatan) {
            $gantiAktif = MahasiswaKegiatan::where('nim', $mahasiswa->nim)
                ->where('id', '!=', $mk->id)
                ->where('status_kegiatan', 'aktif')
                ->latest()->first();

            $mahasiswa->update([
                'kegiatan'       => $gantiAktif?->kegiatan,
                'tahun_akademik' => $gantiAktif?->tahun_akademik,
            ]);

            if ($gantiAktif) $gantiAktif->update(['is_active' => true]);
        }

        return redirect()->route('mahasiswa.daftar-kegiatan.page')
            ->with('success', 'Pendaftaran ' . $mk->kegiatan . ' berhasil dibatalkan.');
    }

    public function editProfil()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->firstOrFail();
        return view('mahasiswa.edit_profil', compact('mahasiswa'));
    }

    public function updateProfil(Request $request)
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->firstOrFail();

        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:mahasiswas,email,' . $mahasiswa->id,
            'kampus'        => 'required|string|max:255',
            'kecamatan'     => 'required|string|max:255',
            'prodi'         => 'required|in:PGSD,PBSI,PBI,SI,ME,PARBUD,HUKUM',
            'pembayaranKRS' => 'required|string|max:500',
            'KRS'           => 'required|string|max:500',
        ]);

        $mahasiswa->update($request->only(['nama', 'email', 'kampus', 'kecamatan', 'prodi', 'pembayaranKRS', 'KRS']));

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');
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
