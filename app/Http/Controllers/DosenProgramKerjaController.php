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

    public function dashboard()
    {
        $dosen = Auth::guard('dosen')->user();
        $mahasiswaBimbinganNim = $this->getMahasiswaBimbingan();

        $totalMahasiswa = $mahasiswaBimbinganNim->count();
        $totalProgram = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->count();
        $mahasiswaDenganProgram = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->distinct('nim')->count('nim');
        $mahasiswaTanpaProgram = $totalMahasiswa - $mahasiswaDenganProgram;

        $statistikStatus = [
            'rencana' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'rencana')->count(),
            'sedang_berjalan' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'sedang_berjalan')->count(),
            'selesai' => IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)->where('status', 'selesai')->count(),
        ];

        $recentPrograms = IndividuProgramKerja::whereIn('nim', $mahasiswaBimbinganNim)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dosen.program-kerja.dashboard', compact(
            'totalMahasiswa',
            'totalProgram',
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

    public function monevDetail($type, $programId)
    {
        $dosen = Auth::guard('dosen')->user();

        $monev = DosenMonev::where('nidn', $dosen->nidn)
            ->where('monev_type', $type)
            ->where('program_id', $programId)
            ->firstOrFail();

        if ($type === 'individu') {
            $program = IndividuProgramKerja::with('mahasiswa')->findOrFail($programId);
            $luarans = $program->luarans;
            return view('dosen.program-kerja.monev-detail', compact('program', 'monev', 'type', 'luarans'));
        } else {
            $program = KelompokProgramKerja::with('mahasiswaKetua')->findOrFail($programId);
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

        $request->validate([
            'nilai' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:5000',
            'tanggal_monev' => 'nullable|date',
            'foto_monev' => 'nullable|array',
            'foto_monev.*' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ], [
            'nilai.numeric' => 'Nilai harus berupa angka',
            'nilai.min' => 'Nilai minimal adalah 0',
            'nilai.max' => 'Nilai maksimal adalah 100',
            'foto_monev.*.image' => 'Berkas harus berupa gambar (JPG, PNG, WEBP)',
            'foto_monev.*.max' => 'Ukuran setiap foto maksimal 10MB',
        ]);

        $currentPhotos = is_array($monev->foto_monev) ? $monev->foto_monev : [];

        // Upload new photos if present
        if ($request->hasFile('foto_monev')) {
            foreach ($request->file('foto_monev') as $file) {
                if ($file->isValid()) {
                    $filename = 'monev_' . $type . '_' . $programId . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('monev', $filename, 'public');
                    $currentPhotos[] = $path;
                }
            }
        }

        $monev->nilai = $request->filled('nilai') ? $request->input('nilai') : $monev->nilai;
        $monev->catatan = $request->input('catatan');
        $monev->tanggal_monev = $request->filled('tanggal_monev') ? $request->input('tanggal_monev') : ($monev->tanggal_monev ?: now()->toDateString());
        $monev->foto_monev = $currentPhotos;
        $monev->save();

        // Send notifications
        if ($type === 'individu') {
            $program = IndividuProgramKerja::find($programId);
            if ($program && $program->nim) {
                Notifikasi::kirim(
                    $program->nim,
                    'Hasil Monev Program Kerja',
                    "Dosen Monev ({$dosen->nama}) telah memperbarui catatan & dokumentasi hasil monev untuk program: {$program->judul}",
                    'info'
                );
            }
        } else {
            $program = KelompokProgramKerja::find($programId);
            if ($program) {
                if ($program->nim_ketua) {
                    Notifikasi::kirim(
                        $program->nim_ketua,
                        'Hasil Monev Program Kelompok',
                        "Dosen Monev ({$dosen->nama}) telah mengunggah catatan & foto hasil monev untuk kelompok: {$program->judul}",
                        'info'
                    );
                }
                $anggota = $program->anggota();
                foreach ($anggota as $member) {
                    if ($member->nim !== $program->nim_ketua) {
                        Notifikasi::kirim(
                            $member->nim,
                            'Hasil Monev Program Kelompok',
                            "Dosen Monev ({$dosen->nama}) telah mengunggah catatan & foto hasil monev untuk kelompok: {$program->judul}",
                            'info'
                        );
                    }
                }
            }
        }

        return back()->with('success', 'Catatan, nilai, dan foto dokumentasi hasil monev berhasil disimpan!');
    }

    public function deleteFotoMonev(Request $request, $type, $programId, $photoIndex)
    {
        $dosen = Auth::guard('dosen')->user();

        $monev = DosenMonev::where('nidn', $dosen->nidn)
            ->where('monev_type', $type)
            ->where('program_id', $programId)
            ->firstOrFail();

        $photos = is_array($monev->foto_monev) ? $monev->foto_monev : [];

        if (isset($photos[$photoIndex])) {
            $photoPath = $photos[$photoIndex];
            Storage::disk('public')->delete($photoPath);
            array_splice($photos, $photoIndex, 1);
            $monev->foto_monev = array_values($photos);
            $monev->save();

            return back()->with('success', 'Foto hasil monev berhasil dihapus.');
        }

        return back()->with('error', 'Foto tidak ditemukan.');
    }
}

