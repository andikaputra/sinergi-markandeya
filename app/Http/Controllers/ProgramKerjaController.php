<?php

namespace App\Http\Controllers;

use App\Models\IndividuProgramKerja;
use App\Models\KelompokProgramKerja;
use App\Models\IndividuLuaran;
use App\Models\KelompokLuaran;
use App\Models\Mahasiswa;
use App\Models\PenempatanKkn;
use App\Models\PenempatanPpl;
use App\Models\PenempatanPkl;
use App\Models\PenempatanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:mahasiswa');
    }

    public function index()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $kegiatan = $mahasiswa->kegiatan;

        $individuPrograms = IndividuProgramKerja::where('nim', $mahasiswa->nim)
            ->where('kategori', $kegiatan)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page_individu', 1);

        $kelompokPrograms = KelompokProgramKerja::where('kategori', $kegiatan)
            ->whereHas('mahasiswaKetua', function ($q) use ($mahasiswa) {
                $q->orWhere('nim_ketua', $mahasiswa->nim)
                  ->orWhereIn('nim', function ($q2) use ($kegiatan, $mahasiswa) {
                      $table = match($kegiatan) {
                          'kkn' => 'penempatan_kkns',
                          'ppl' => 'penempatan_ppls',
                          'pkl' => 'penempatan_pkls',
                          'magang' => 'penempatan_mamangs',
                          default => 'penempatan_kkns'
                      };
                      $column = match($kegiatan) {
                          'kkn' => 'lokasi_kkn_id',
                          'ppl' => 'lokasi_ppl_id',
                          'pkl' => 'lokasi_pkl_id',
                          'magang' => 'lokasi_magang_id',
                          default => 'lokasi_kkn_id'
                      };
                      $q2->select('nim')->from($table)->where($column, function ($q3) use ($table, $column, $mahasiswa) {
                          $q3->select($column)->from($table)->where('nim', $mahasiswa->nim);
                      });
                  });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page_kelompok', 1);

        $statistikIndividu = [
            'total' => IndividuProgramKerja::where('nim', $mahasiswa->nim)->where('kategori', $kegiatan)->count(),
            'rencana' => IndividuProgramKerja::where('nim', $mahasiswa->nim)->where('kategori', $kegiatan)->where('status', 'rencana')->count(),
            'sedang_berjalan' => IndividuProgramKerja::where('nim', $mahasiswa->nim)->where('kategori', $kegiatan)->where('status', 'sedang_berjalan')->count(),
            'selesai' => IndividuProgramKerja::where('nim', $mahasiswa->nim)->where('kategori', $kegiatan)->where('status', 'selesai')->count(),
        ];

        $statistikKelompok = [
            'total' => KelompokProgramKerja::where('kategori', $kegiatan)->count(),
            'rencana' => KelompokProgramKerja::where('kategori', $kegiatan)->where('status', 'rencana')->count(),
            'sedang_berjalan' => KelompokProgramKerja::where('kategori', $kegiatan)->where('status', 'sedang_berjalan')->count(),
            'selesai' => KelompokProgramKerja::where('kategori', $kegiatan)->where('status', 'selesai')->count(),
        ];

        return view('mahasiswa.program-kerja.index', compact('individuPrograms', 'kelompokPrograms', 'statistikIndividu', 'statistikKelompok', 'kegiatan'));
    }

    // Individu Methods
    public function createIndividu()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        return view('mahasiswa.program-kerja.individu.create', compact('mahasiswa'));
    }

    public function storeIndividu(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $kegiatan = $mahasiswa->kegiatan;

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
        ]);

        IndividuProgramKerja::create(array_merge($validated, [
            'nim' => $mahasiswa->nim,
            'kategori' => $kegiatan,
            'status' => 'rencana',
        ]));

        return redirect()->route('program-kerja.index')->with('success', 'Program kerja individu berhasil dibuat');
    }

    public function showIndividu(IndividuProgramKerja $individuProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($individuProgramKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $luarans = $individuProgramKerja->luarans()->orderBy('created_at', 'desc')->get();
        $statistikLuaran = [
            'total' => $luarans->count(),
            'selesai' => $luarans->where('status', 'selesai')->count(),
        ];
        $dosenMonev = $individuProgramKerja->dosenMonev;

        return view('mahasiswa.program-kerja.individu.show', compact('individuProgramKerja', 'luarans', 'statistikLuaran', 'dosenMonev'));
    }

    public function editIndividu(IndividuProgramKerja $individuProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($individuProgramKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        return view('mahasiswa.program-kerja.individu.edit', compact('individuProgramKerja'));
    }

    public function updateIndividu(Request $request, IndividuProgramKerja $individuProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($individuProgramKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:rencana,sedang_berjalan,selesai,tunda',
        ]);

        $individuProgramKerja->update($validated);

        return redirect()->route('program-kerja.show-individu', $individuProgramKerja)->with('success', 'Program kerja berhasil diupdate');
    }

    public function destroyIndividu(IndividuProgramKerja $individuProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($individuProgramKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $individuProgramKerja->delete();

        return redirect()->route('program-kerja.index')->with('success', 'Program kerja berhasil dihapus');
    }

    // Kelompok Methods
    public function createKelompok()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        return view('mahasiswa.program-kerja.kelompok.create', compact('mahasiswa'));
    }

    public function storeKelompok(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $kegiatan = $mahasiswa->kegiatan;

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
        ]);

        KelompokProgramKerja::create(array_merge($validated, [
            'nim_ketua' => $mahasiswa->nim,
            'kategori' => $kegiatan,
            'status' => 'rencana',
        ]));

        return redirect()->route('program-kerja.index')->with('success', 'Program kerja kelompok berhasil dibuat');
    }

    public function showKelompok(KelompokProgramKerja $kelompokProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $anggota = $kelompokProgramKerja->anggota();
        $isAnggota = $anggota->contains('nim', $mahasiswa->nim);

        if (!$isAnggota && $kelompokProgramKerja->nim_ketua !== $mahasiswa->nim) {
            abort(403);
        }

        $luarans = $kelompokProgramKerja->luarans()->orderBy('created_at', 'desc')->get();
        $statistikLuaran = [
            'total' => $luarans->count(),
            'selesai' => $luarans->where('status', 'selesai')->count(),
        ];
        $dosenMonev = $kelompokProgramKerja->dosenMonev;

        return view('mahasiswa.program-kerja.kelompok.show', compact('kelompokProgramKerja', 'anggota', 'luarans', 'statistikLuaran', 'dosenMonev'));
    }

    public function editKelompok(KelompokProgramKerja $kelompokProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($kelompokProgramKerja->nim_ketua !== $mahasiswa->nim) {
            abort(403);
        }

        return view('mahasiswa.program-kerja.kelompok.edit', compact('kelompokProgramKerja'));
    }

    public function updateKelompok(Request $request, KelompokProgramKerja $kelompokProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($kelompokProgramKerja->nim_ketua !== $mahasiswa->nim) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:rencana,sedang_berjalan,selesai,tunda',
        ]);

        $kelompokProgramKerja->update($validated);

        return redirect()->route('program-kerja.show-kelompok', $kelompokProgramKerja)->with('success', 'Program kerja berhasil diupdate');
    }

    public function destroyKelompok(KelompokProgramKerja $kelompokProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($kelompokProgramKerja->nim_ketua !== $mahasiswa->nim) {
            abort(403);
        }

        $kelompokProgramKerja->delete();

        return redirect()->route('program-kerja.index')->with('success', 'Program kerja berhasil dihapus');
    }

    // Luaran Methods
    public function storeLuaranIndividu(Request $request, IndividuProgramKerja $individuProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($individuProgramKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tipe' => 'required|string|max:100',
            'tanggal_selesai' => 'required|date',
            'file_path' => 'nullable|url:http,https',
        ]);

        $individuProgramKerja->luarans()->create($validated);

        return back()->with('success', 'Luaran berhasil ditambahkan');
    }

    public function storeLuaranKelompok(Request $request, KelompokProgramKerja $kelompokProgramKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $anggota = $kelompokProgramKerja->anggota();
        $isAnggota = $anggota->contains('nim', $mahasiswa->nim);

        if (!$isAnggota && $kelompokProgramKerja->nim_ketua !== $mahasiswa->nim) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tipe' => 'required|string|max:100',
            'tanggal_selesai' => 'required|date',
            'file_path' => 'nullable|url:http,https',
        ]);

        $kelompokProgramKerja->luarans()->create($validated);

        return back()->with('success', 'Luaran berhasil ditambahkan');
    }

    public function updateLuaranStatus(Request $request, $type, $luaranId)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $validated = $request->validate([
            'status' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai',
            'persentase_selesai' => 'required|integer|min:0|max:100',
        ]);

        if ($type === 'individu') {
            $luaran = IndividuLuaran::findOrFail($luaranId);
            if ($luaran->programKerja->nim !== $mahasiswa->nim) {
                abort(403);
            }
        } else {
            $luaran = KelompokLuaran::findOrFail($luaranId);
            $anggota = $luaran->programKerja->anggota();
            $isAnggota = $anggota->contains('nim', $mahasiswa->nim);
            if (!$isAnggota && $luaran->programKerja->nim_ketua !== $mahasiswa->nim) {
                abort(403);
            }
        }

        $luaran->update($validated);

        return back()->with('success', 'Status luaran berhasil diupdate');
    }

    public function deleteLuaran($type, $luaranId)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($type === 'individu') {
            $luaran = IndividuLuaran::findOrFail($luaranId);
            if ($luaran->programKerja->nim !== $mahasiswa->nim) {
                abort(403);
            }
        } else {
            $luaran = KelompokLuaran::findOrFail($luaranId);
            $anggota = $luaran->programKerja->anggota();
            $isAnggota = $anggota->contains('nim', $mahasiswa->nim);
            if (!$isAnggota && $luaran->programKerja->nim_ketua !== $mahasiswa->nim) {
                abort(403);
            }
        }

        $luaran->delete();

        return back()->with('success', 'Luaran berhasil dihapus');
    }
}
