<?php

namespace App\Http\Controllers;

use App\Models\IndividuProgramKerja;
use App\Models\KelompokProgramKerja;
use App\Models\IndividuLuaran;
use App\Models\KelompokLuaran;
use App\Models\DosenMonev;
use App\Models\Mahasiswa;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DosenProgramKerjaController extends Controller
{
    private function getMahasiswaBimbingan()
    {
        $dosen = Auth::guard('dosen')->user();
        return Mahasiswa::whereIn('nim', function ($query) use ($dosen) {
            $query->select('nim')
                ->from('dosen_pembimbings')
                ->where('nidn', $dosen->nidn);
        })->pluck('nim');
    }

    private function getKelompokProgramsQuery()
    {
        $mahasiswaBimbinganNim = $this->getMahasiswaBimbingan();

        $kknLocationIds = \App\Models\PenempatanKkn::whereIn('nim', $mahasiswaBimbinganNim)->pluck('lokasi_kkn_id')->filter();
        $pplLocationIds = \App\Models\PenempatanPpl::whereIn('nim', $mahasiswaBimbinganNim)->pluck('sekolah_id')->filter();
        $pklLocationIds = \App\Models\PenempatanPkl::whereIn('nim', $mahasiswaBimbinganNim)->pluck('lokasi_pkl_id')->filter();
        $magangLocationIds = \App\Models\PenempatanMagang::whereIn('nim', $mahasiswaBimbinganNim)->pluck('lokasi_magang_id')->filter();

        $allGroupNims = collect($mahasiswaBimbinganNim);

        if ($kknLocationIds->isNotEmpty()) {
            $allGroupNims = $allGroupNims->merge(\App\Models\PenempatanKkn::whereIn('lokasi_kkn_id', $kknLocationIds)->pluck('nim'));
        }
        if ($pplLocationIds->isNotEmpty()) {
            $allGroupNims = $allGroupNims->merge(\App\Models\PenempatanPpl::whereIn('sekolah_id', $pplLocationIds)->pluck('nim'));
        }
        if ($pklLocationIds->isNotEmpty()) {
            $allGroupNims = $allGroupNims->merge(\App\Models\PenempatanPkl::whereIn('lokasi_pkl_id', $pklLocationIds)->pluck('nim'));
        }
        if ($magangLocationIds->isNotEmpty()) {
            $allGroupNims = $allGroupNims->merge(\App\Models\PenempatanMagang::whereIn('lokasi_magang_id', $magangLocationIds)->pluck('nim'));
        }

        $allGroupNims = $allGroupNims->unique()->values();

        return KelompokProgramKerja::whereIn('nim_ketua', $allGroupNims);
    }

    public function dashboard()
    {
        $dosen = Auth::guard('dosen')->user();
        $mahasiswaBimbinganNim = $this->getMahasiswaBimbingan();

        $totalMahasiswa = $mahasiswaBimbinganNim->count();
        $totalProgramIndividu = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->count();
        $totalProgramKelompok = $this->getKelompokProgramsQuery()->count();
        $totalProgram = $totalProgramIndividu + $totalProgramKelompok;

        // Mahasiswa yang sudah membuat/tergabung dalam program kerja
        $mhsIndividu = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->pluck('nim');
        $mhsKelompok = collect();
        foreach ($this->getKelompokProgramsQuery()->get() as $kp) {
            $mhsKelompok = $mhsKelompok->merge($kp->anggota()->pluck('nim'));
        }
        $mahasiswaDenganProgram = $mhsIndividu->merge($mhsKelompok)->intersect($mahasiswaBimbinganNim)->unique()->count();
        $mahasiswaTanpaProgram = max(0, $totalMahasiswa - $mahasiswaDenganProgram);

        $kelompokQuery = $this->getKelompokProgramsQuery();

        $statistikStatus = [
            'rencana' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'rencana')->count()
                         + (clone $kelompokQuery)->where('status', 'rencana')->count(),
            'sedang_berjalan' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'sedang_berjalan')->count()
                                 + (clone $kelompokQuery)->where('status', 'sedang_berjalan')->count(),
            'selesai' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'selesai')->count()
                         + (clone $kelompokQuery)->where('status', 'selesai')->count(),
        ];

        $recentIndividu = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($p) {
                $p->program_type = 'individu';
                return $p;
            });

        $recentKelompok = $this->getKelompokProgramsQuery()
            ->with('mahasiswaKetua')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($p) {
                $p->program_type = 'kelompok';
                return $p;
            });

        $recentPrograms = $recentIndividu->merge($recentKelompok)->sortByDesc('created_at')->take(10)->values();

        return view('dosen.program-kerja.dashboard', compact(
            'totalMahasiswa',
            'totalProgram',
            'totalProgramIndividu',
            'totalProgramKelompok',
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

    public function detailMahasiswa($mahasiswa)
    {
        $dosen = Auth::guard('dosen')->user();

        // Resolve Mahasiswa instance if parameter is passed as model, NIM, or ID
        if ($mahasiswa instanceof Mahasiswa) {
            $mhs = $mahasiswa;
        } elseif (is_numeric($mahasiswa) && strlen((string)$mahasiswa) > 8) {
            $mhs = Mahasiswa::where('nim', $mahasiswa)->firstOrFail();
        } else {
            $mhs = Mahasiswa::where('id', $mahasiswa)->orWhere('nim', $mahasiswa)->firstOrFail();
        }

        $isBimbinganDosen = \App\Models\DosenPembimbing::where('nidn', $dosen->nidn)
            ->where('nim', $mhs->nim)
            ->exists();

        if (!$isBimbinganDosen) {
            abort(403, 'Anda tidak berwenang mengakses data mahasiswa ini');
        }

        // Program Kerja Individu
        $individuPrograms = IndividuProgramKerja::where('nim', $mhs->nim)
            ->with(['luarans', 'dosenMonev.dosen'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Cari Program Kerja Kelompok mahasiswa jika ada
        $kegiatanLower = strtolower($mhs->kegiatan ?? '');
        $table = match($kegiatanLower) {
            'kkn' => 'pembagian_lokasi_kkn',
            'ppl' => 'Penempatan_ppl',
            'pkl' => 'penempatan_pkls',
            'magang' => 'penempatan_magangs',
            default => null
        };
        $column = match($kegiatanLower) {
            'kkn' => 'lokasi_kkn_id',
            'ppl' => 'sekolah_id',
            'pkl' => 'lokasi_pkl_id',
            'magang' => 'lokasi_magang_id',
            default => null
        };

        $groupNims = collect([$mhs->nim]);
        if ($table && $column) {
            $myLocationId = \Illuminate\Support\Facades\DB::table($table)->where('nim', $mhs->nim)->value($column);
            if ($myLocationId) {
                $groupNims = \Illuminate\Support\Facades\DB::table($table)->where($column, $myLocationId)->pluck('nim');
            }
        }

        $kelompokPrograms = KelompokProgramKerja::whereIn('nim_ketua', $groupNims)
            ->with(['luarans', 'dosenMonev.dosen', 'mahasiswaKetua'])
            ->orderBy('created_at', 'desc')
            ->get();

        $individuLuarans = IndividuLuaran::whereIn('individu_program_kerja_id', $individuPrograms->pluck('id'))
            ->with('programKerja')
            ->orderBy('created_at', 'desc')
            ->get();

        $kelompokLuarans = KelompokLuaran::whereIn('kelompok_program_kerja_id', $kelompokPrograms->pluck('id'))
            ->with('programKerja')
            ->orderBy('created_at', 'desc')
            ->get();

        // Alias variables for blade compatibility
        $programs = $individuPrograms;
        $luarans = $individuLuarans;
        $mahasiswa = $mhs;

        return view('dosen.program-kerja.detail-mahasiswa', compact(
            'mahasiswa',
            'individuPrograms',
            'kelompokPrograms',
            'individuLuarans',
            'kelompokLuarans',
            'programs',
            'luarans'
        ));
    }

    public function semuaProgram(Request $request)
    {
        $mahasiswaBimbingan = $this->getMahasiswaBimbingan();

        $individuPrograms = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbingan)
            ->with(['mahasiswa', 'luarans'])
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'page_individu');

        $kelompokPrograms = $this->getKelompokProgramsQuery()
            ->with(['mahasiswaKetua', 'luarans'])
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'page_kelompok');

        $programs = $individuPrograms; // For backward compatibility

        return view('dosen.program-kerja.semua-program', compact('individuPrograms', 'kelompokPrograms', 'programs'));
    }

    public function semuaLuaran(Request $request)
    {
        $mahasiswaBimbingan = $this->getMahasiswaBimbingan();

        $individuLuarans = IndividuLuaran::whereIn('individu_program_kerja_id', function ($query) use ($mahasiswaBimbingan) {
            $query->select('id')
                ->from('individu_program_kerjas')
                ->whereIn('nim', $mahasiswaBimbingan);
        })
        ->with('programKerja.mahasiswa')
        ->orderBy('created_at', 'desc')
        ->paginate(15, ['*'], 'page_individu_luaran');

        $kelompokProgramIds = $this->getKelompokProgramsQuery()->pluck('id');
        $kelompokLuarans = KelompokLuaran::whereIn('kelompok_program_kerja_id', $kelompokProgramIds)
            ->with('programKerja.mahasiswaKetua')
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'page_kelompok_luaran');

        $luarans = $individuLuarans; // For backward compatibility

        return view('dosen.program-kerja.semua-luaran', compact('individuLuarans', 'kelompokLuarans', 'luarans'));
    }

    // Dosen Monev Methods
    public function monevDashboard()
    {
        $dosen = Auth::guard('dosen')->user();
        $monevPrograms = DosenMonev::where('nidn', $dosen->nidn)
            ->with(['mahasiswa', 'programKerja', 'lokasiKkn', 'lokasiPpl', 'lokasiPkl', 'lokasiMagang'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        $totalTugas = DosenMonev::where('nidn', $dosen->nidn)->count();
        $totalSelesai = DosenMonev::where('nidn', $dosen->nidn)
            ->where(function ($q) {
                $q->whereNotNull('catatan')->orWhereNotNull('nilai')->orWhereNotNull('foto_monev');
            })->count();
        $totalBelum = $totalTugas - $totalSelesai;

        return view('dosen.program-kerja.monev-dashboard', compact('monevPrograms', 'totalTugas', 'totalSelesai', 'totalBelum'));
    }

    public function monevDetail(Request $request, $idOrType, $programId = null)
    {
        $dosen = Auth::guard('dosen')->user();

        if ($programId) {
            $monev = DosenMonev::where('nidn', $dosen->nidn)
                ->where('monev_type', $idOrType)
                ->where(function ($q) use ($programId) {
                    $q->where('program_id', $programId)->orWhere('id', $programId);
                })
                ->firstOrFail();
        } else {
            $monev = DosenMonev::where('nidn', $dosen->nidn)
                ->where('id', $idOrType)
                ->firstOrFail();
        }

        $type = $monev->monev_type;

        if ($type === 'individu') {
            $program = $monev->program_id ? IndividuProgramKerja::with('mahasiswa')->find($monev->program_id) : null;
            $mahasiswa = $monev->mahasiswa ?: ($program?->mahasiswa ?: Mahasiswa::where('nim', $monev->nim)->first());
            $luarans = $program ? $program->luarans : collect();
            return view('dosen.program-kerja.monev-detail', compact('program', 'monev', 'type', 'mahasiswa', 'luarans'));
        } else {
            $program = $monev->program_id ? KelompokProgramKerja::with('mahasiswaKetua')->find($monev->program_id) : null;
            $anggota = $program ? $program->anggota() : collect();
            $luarans = $program ? $program->luarans : collect();
            $lokasi = $monev->lokasiKkn ?: ($monev->lokasiPpl ?: ($monev->lokasiPkl ?: $monev->lokasiMagang));
            return view('dosen.program-kerja.monev-detail', compact('program', 'monev', 'type', 'anggota', 'luarans', 'lokasi'));
        }
    }

    public function inputNilaiMonev(Request $request, $idOrType, $programId = null)
    {
        $dosen = Auth::guard('dosen')->user();

        if ($programId) {
            $monev = DosenMonev::where('nidn', $dosen->nidn)
                ->where('monev_type', $idOrType)
                ->where(function ($q) use ($programId) {
                    $q->where('program_id', $programId)->orWhere('id', $programId);
                })
                ->firstOrFail();
        } else {
            $monev = DosenMonev::where('nidn', $dosen->nidn)
                ->where('id', $idOrType)
                ->firstOrFail();
        }

        $type = $monev->monev_type;

        $request->validate([
            'nilai' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:5000',
            'tanggal_monev' => 'nullable|date',
            'link_monev' => 'nullable|url|max:1000',
            'foto_monev' => 'nullable|array',
            'foto_monev.*' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ], [
            'nilai.numeric' => 'Nilai harus berupa angka',
            'nilai.min' => 'Nilai minimal adalah 0',
            'nilai.max' => 'Nilai maksimal adalah 100',
            'link_monev.url' => 'Format tautan Google Drive / dokumentasi harus berupa URL yang valid (diawali https://)',
            'foto_monev.*.image' => 'Berkas harus berupa gambar (JPG, PNG, WEBP)',
            'foto_monev.*.max' => 'Ukuran setiap foto maksimal 10MB',
        ]);

        $currentPhotos = is_array($monev->foto_monev) ? $monev->foto_monev : [];

        // Upload new photos if present
        if ($request->hasFile('foto_monev')) {
            foreach ($request->file('foto_monev') as $file) {
                if ($file->isValid()) {
                    $filename = 'monev_' . $type . '_' . $monev->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('monev', $filename, 'public');
                    $currentPhotos[] = $path;
                }
            }
        }

        $monev->nilai = $request->filled('nilai') ? $request->input('nilai') : $monev->nilai;
        $monev->catatan = $request->input('catatan');
        $monev->tanggal_monev = $request->filled('tanggal_monev') ? $request->input('tanggal_monev') : ($monev->tanggal_monev ?: now()->toDateString());
        $monev->link_monev = $request->input('link_monev');
        $monev->foto_monev = $currentPhotos;
        $monev->save();

        // Send notifications
        if ($type === 'individu') {
            $nim = $monev->nim ?: ($monev->programKerja?->nim);
            if ($nim) {
                Notifikasi::kirim(
                    $nim,
                    'Hasil Monev Program Kerja',
                    "Dosen Monev ({$dosen->nama}) telah memperbarui catatan & dokumentasi hasil monev.",
                    'info'
                );
            }
        } else {
            if ($monev->program_id) {
                $program = KelompokProgramKerja::find($monev->program_id);
                if ($program && $program->nim_ketua) {
                    Notifikasi::kirim($program->nim_ketua, 'Hasil Monev Kelompok', "Dosen Monev ({$dosen->nama}) telah mengunggah catatan & foto hasil monev.", 'info');
                }
            } elseif ($monev->lokasi_id) {
                $nims = match($monev->kegiatan) {
                    'kkn' => \App\Models\PenempatanKkn::where('lokasi_kkn_id', $monev->lokasi_id)->pluck('nim'),
                    'ppl' => \App\Models\PenempatanPpl::where('sekolah_id', $monev->lokasi_id)->pluck('nim'),
                    'pkl' => \App\Models\PenempatanPkl::where('lokasi_pkl_id', $monev->lokasi_id)->pluck('nim'),
                    'magang' => \App\Models\PenempatanMagang::where('lokasi_magang_id', $monev->lokasi_id)->pluck('nim'),
                    default => collect(),
                };
                foreach ($nims as $nim) {
                    Notifikasi::kirim($nim, 'Hasil Monev Kelompok/Lokasi', "Dosen Monev ({$dosen->nama}) telah mengunggah catatan & foto hasil monev.", 'info');
                }
            }
        }

        return back()->with('success', 'Catatan, nilai, dan foto dokumentasi hasil monev berhasil disimpan!');
    }

    public function deleteFotoMonev(Request $request, $idOrType, $programIdOrPhotoIndex = null, $photoIndex = null)
    {
        $dosen = Auth::guard('dosen')->user();

        if ($photoIndex !== null) {
            $monev = DosenMonev::where('nidn', $dosen->nidn)
                ->where('monev_type', $idOrType)
                ->where('program_id', $programIdOrPhotoIndex)
                ->firstOrFail();
            $targetIndex = $photoIndex;
        } else {
            $monev = DosenMonev::where('nidn', $dosen->nidn)
                ->where('id', $idOrType)
                ->firstOrFail();
            $targetIndex = $programIdOrPhotoIndex;
        }

        $photos = is_array($monev->foto_monev) ? $monev->foto_monev : [];

        if (isset($photos[$targetIndex])) {
            $photoPath = $photos[$targetIndex];
            Storage::disk('public')->delete($photoPath);
            array_splice($photos, $targetIndex, 1);
            $monev->foto_monev = array_values($photos);
            $monev->save();

            return back()->with('success', 'Foto hasil monev berhasil dihapus.');
        }

        return back()->with('error', 'Foto tidak ditemukan.');
    }
}

