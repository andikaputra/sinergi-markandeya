<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\DosenPenguji;
use App\Models\Jurnal;
use App\Models\TahunAkademik;
use App\Models\LokasiKkn;
use App\Models\LokasiPpl;
use App\Models\LokasiPkl;
use App\Models\LokasiMagang;
use App\Models\PenempatanKkn;
use App\Models\PenempatanPpl;
use App\Models\PenempatanPkl;
use App\Models\PenempatanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenPengujiController extends Controller
{
    public function adminIndex(Request $request)
    {
        $allowedKegiatan = Auth::user()->getAllowedKegiatan();
        $reqKegiatan = $request->input('kegiatan');
        if ($reqKegiatan) {
            $matched = collect($allowedKegiatan)->first(fn($k) => strcasecmp($k, $reqKegiatan) === 0);
            $selectedKegiatan = $matched ?: strtoupper($reqKegiatan);
        } else {
            $selectedKegiatan = in_array('KKN', $allowedKegiatan) ? 'KKN' : ($allowedKegiatan[0] ?? 'KKN');
        }

        $selectedType = $request->input('type', 'perorangan'); // 'perorangan' or 'kelompok'
        $filterKegiatan = [$selectedKegiatan];

        // Query mahasiswa belum ada dosen penguji untuk mode perorangan
        $mahasiswas = Mahasiswa::whereDoesntHave('dosenPenguji')
            ->withKegiatanIn($filterKegiatan)
            ->with([
                'penempatankkn.lokasikkn',
                'penempatanppl.lokasippl',
                'penempatanpkl.lokasipkl',
                'penempatanmagang.lokasimagang',
                'activeKegiatan',
            ])
            ->orderBy('nama')
            ->get();

        // Build data kelompok berdasarkan lokasi penempatan mahasiswa untuk kegiatan terpilih
        $kelompoks = collect();

        if (in_array('KKN', $filterKegiatan)) {
            $lokasis = LokasiKkn::with(['penempatankkn.mahasiswa' => function($q) {
                $q->with(['dosenPenguji.dosen', 'activeKegiatan']);
            }])->orderBy('desa')->get();

            foreach ($lokasis as $lokasi) {
                $members = $lokasi->penempatankkn->map(fn($p) => $p->mahasiswa)->filter();
                $unassigned = $members->filter(fn($m) => !$m->dosenPenguji);
                $assigned = $members->filter(fn($m) => (bool)$m->dosenPenguji);

                $kelompoks->push((object)[
                    'id' => 'KKN_' . $lokasi->id,
                    'raw_id' => $lokasi->id,
                    'kegiatan' => 'KKN',
                    'nama' => 'Desa ' . $lokasi->desa,
                    'detail' => trim(($lokasi->kecamatan ? 'Kec. ' . $lokasi->kecamatan : '') . ($lokasi->kabupaten ? ', ' . $lokasi->kabupaten : ''), ' ,'),
                    'members' => $members,
                    'unassigned_members' => $unassigned,
                    'assigned_members' => $assigned,
                    'total_count' => $members->count(),
                    'unassigned_count' => $unassigned->count(),
                    'assigned_count' => $assigned->count(),
                    'is_fully_assigned' => $members->isNotEmpty() && $unassigned->isEmpty(),
                    'is_empty' => $members->isEmpty(),
                ]);
            }
        }

        if (in_array('PPL', $filterKegiatan)) {
            $lokasis = LokasiPpl::with(['penempatanppl.mahasiswa' => function($q) {
                $q->with(['dosenPenguji.dosen', 'activeKegiatan']);
            }])->orderBy('Sekolah')->get();

            foreach ($lokasis as $lokasi) {
                $members = $lokasi->penempatanppl->map(fn($p) => $p->mahasiswa)->filter();
                $unassigned = $members->filter(fn($m) => !$m->dosenPenguji);
                $assigned = $members->filter(fn($m) => (bool)$m->dosenPenguji);

                $kelompoks->push((object)[
                    'id' => 'PPL_' . $lokasi->id,
                    'raw_id' => $lokasi->id,
                    'kegiatan' => 'PPL',
                    'nama' => $lokasi->Sekolah ?? $lokasi->sekolah ?? $lokasi->nama_sekolah ?? ('Sekolah #' . $lokasi->id),
                    'detail' => trim(($lokasi->kecamatan ? 'Kec. ' . $lokasi->kecamatan : '') . ($lokasi->kabupaten ? ', ' . $lokasi->kabupaten : '') . ($lokasi->alamat ? ' - ' . $lokasi->alamat : ''), ' ,-'),
                    'members' => $members,
                    'unassigned_members' => $unassigned,
                    'assigned_members' => $assigned,
                    'total_count' => $members->count(),
                    'unassigned_count' => $unassigned->count(),
                    'assigned_count' => $assigned->count(),
                    'is_fully_assigned' => $members->isNotEmpty() && $unassigned->isEmpty(),
                    'is_empty' => $members->isEmpty(),
                ]);
            }
        }

        if (in_array('PKL', $filterKegiatan)) {
            $lokasis = LokasiPkl::with(['penempatanpkl.mahasiswa' => function($q) {
                $q->with(['dosenPenguji.dosen', 'activeKegiatan']);
            }])->orderBy('nama_instansi')->get();

            foreach ($lokasis as $lokasi) {
                $members = $lokasi->penempatanpkl->map(fn($p) => $p->mahasiswa)->filter();
                $unassigned = $members->filter(fn($m) => !$m->dosenPenguji);
                $assigned = $members->filter(fn($m) => (bool)$m->dosenPenguji);

                $kelompoks->push((object)[
                    'id' => 'PKL_' . $lokasi->id,
                    'raw_id' => $lokasi->id,
                    'kegiatan' => 'PKL',
                    'nama' => $lokasi->nama_instansi ?? ('Instansi #' . $lokasi->id),
                    'detail' => $lokasi->alamat ?? '-',
                    'members' => $members,
                    'unassigned_members' => $unassigned,
                    'assigned_members' => $assigned,
                    'total_count' => $members->count(),
                    'unassigned_count' => $unassigned->count(),
                    'assigned_count' => $assigned->count(),
                    'is_fully_assigned' => $members->isNotEmpty() && $unassigned->isEmpty(),
                    'is_empty' => $members->isEmpty(),
                ]);
            }
        }

        if (in_array('Magang', $filterKegiatan)) {
            $lokasis = LokasiMagang::with(['penempatanmagang.mahasiswa' => function($q) {
                $q->with(['dosenPenguji.dosen', 'activeKegiatan']);
            }])->orderBy('nama_instansi')->get();

            foreach ($lokasis as $lokasi) {
                $members = $lokasi->penempatanmagang->map(fn($p) => $p->mahasiswa)->filter();
                $unassigned = $members->filter(fn($m) => !$m->dosenPenguji);
                $assigned = $members->filter(fn($m) => (bool)$m->dosenPenguji);

                $kelompoks->push((object)[
                    'id' => 'MAGANG_' . $lokasi->id,
                    'raw_id' => $lokasi->id,
                    'kegiatan' => 'Magang',
                    'nama' => $lokasi->nama_instansi ?? ('Instansi #' . $lokasi->id),
                    'detail' => $lokasi->alamat ?? '-',
                    'members' => $members,
                    'unassigned_members' => $unassigned,
                    'assigned_members' => $assigned,
                    'total_count' => $members->count(),
                    'unassigned_count' => $unassigned->count(),
                    'assigned_count' => $assigned->count(),
                    'is_fully_assigned' => $members->isNotEmpty() && $unassigned->isEmpty(),
                    'is_empty' => $members->isEmpty(),
                ]);
            }
        }

        $dosens = Dosen::orderBy('nama', 'asc')->get();

        $assignments = DosenPenguji::with([
                'mahasiswa.penempatankkn.lokasikkn',
                'mahasiswa.penempatanppl.lokasippl',
                'mahasiswa.penempatanpkl.lokasipkl',
                'mahasiswa.penempatanmagang.lokasimagang',
                'mahasiswa.activeKegiatan',
                'dosen'
            ])
            ->whereHas('mahasiswa', fn($q) => $q->withKegiatanIn($filterKegiatan))
            ->latest('updated_at')
            ->get();

        return view('admin.assigndosenpenguji', compact(
            'mahasiswas',
            'kelompoks',
            'dosens',
            'assignments',
            'allowedKegiatan',
            'selectedKegiatan',
            'selectedType'
        ));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'nidn' => 'required|exists:dosens,nidn',
            'nims' => 'nullable|array',
            'kelompok_ids' => 'nullable|array',
            'kegiatan' => 'nullable|string',
            'type' => 'nullable|string',
        ], [
            'nidn.required' => 'Pilih dosen penguji terlebih dahulu.',
            'nidn.exists' => 'Dosen yang dipilih tidak valid.',
        ]);

        $kegiatan = $request->input('kegiatan', 'KKN');
        $type = $request->input('type', 'perorangan');

        $dosen = Dosen::where('nidn', $request->nidn)->firstOrFail();
        $nimsToAssign = collect($request->input('nims', []));

        // Jika ada kelompok_ids, ambil semua NIM anggota kelompok tersebut
        if ($request->has('kelompok_ids') && is_array($request->kelompok_ids)) {
            foreach ($request->kelompok_ids as $groupId) {
                $parts = explode('_', $groupId, 2);
                if (count($parts) === 2) {
                    $grpKeg = strtoupper($parts[0]);
                    $locId = $parts[1];

                    $memberNims = match ($grpKeg) {
                        'KKN' => PenempatanKkn::where('lokasi_kkn_id', $locId)->pluck('nim'),
                        'PPL' => PenempatanPpl::where('sekolah_id', $locId)->pluck('nim'),
                        'PKL' => PenempatanPkl::where('lokasi_pkl_id', $locId)->pluck('nim'),
                        'MAGANG' => PenempatanMagang::where('lokasi_magang_id', $locId)->pluck('nim'),
                        default => collect(),
                    };

                    $nimsToAssign = $nimsToAssign->merge($memberNims);
                }
            }
        }

        $nimsToAssign = $nimsToAssign->unique()->filter()->values();

        if ($nimsToAssign->isEmpty()) {
            return redirect()->route('assign.dosenpenguji', ['kegiatan' => $kegiatan, 'type' => $type])
                ->with('error', 'Pilih minimal satu mahasiswa atau kelompok untuk di-plot!');
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($nimsToAssign as $nim) {
                if (Mahasiswa::where('nim', $nim)->exists()) {
                    DosenPenguji::updateOrCreate(
                        ['nim' => $nim],
                        ['nidn' => $request->nidn]
                    );
                    $count++;
                }
            }
            DB::commit();

            return redirect()->route('assign.dosenpenguji', ['kegiatan' => $kegiatan, 'type' => $type])
                ->with('success', "Berhasil mem-plot {$count} mahasiswa kegiatan {$kegiatan} ke Dosen Penguji {$dosen->nama}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('assign.dosenpenguji', ['kegiatan' => $kegiatan, 'type' => $type])
                ->with('error', 'Gagal memproses plotting penguji: ' . $e->getMessage());
        }
    }

    public function adminDelete($id)
    {
        $assignment = DosenPenguji::findOrFail($id);
        $assignment->delete();
        return redirect()->back()->with('success', 'Plotting Dosen Penguji berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate(['file_csv' => 'required|mimes:csv,txt|max:2048']);

        $file = $request->file('file_csv');
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle);

        $count = 0;
        $errors = [];
        $row = 1;

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                if (count($data) < 2) { $errors[] = "Baris $row: kolom kurang"; continue; }

                $nim = trim($data[0]);
                $nidn = trim($data[1]);

                if (!Mahasiswa::where('nim', $nim)->exists()) { $errors[] = "Baris $row: NIM $nim tidak ditemukan"; continue; }
                if (!Dosen::where('nidn', $nidn)->exists()) { $errors[] = "Baris $row: NIDN $nidn tidak ditemukan"; continue; }

                DosenPenguji::updateOrCreate(['nim' => $nim], ['nidn' => $nidn]);
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
        fclose($handle);

        $message = "Berhasil mengimpor $count plotting dosen penguji!";
        if (!empty($errors)) {
            $message .= ' (' . count($errors) . ' baris dilewati: ' . implode('; ', array_slice($errors, 0, 5)) . ')';
        }
        return redirect()->back()->with('success', $message);
    }

    public function dosenIndex(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        $tahunAkademiks = TahunAkademik::orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
        $activeTA = TahunAkademik::active();

        $selectedTA = $request->input('tahun_akademik', $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null);
        $selectedKegiatan = $request->input('kegiatan');

        $query = DosenPenguji::where('nidn', $dosen->nidn)
            ->with(['mahasiswa.publikasis', 'mahasiswa.penempatankkn.lokasikkn', 'mahasiswa.penempatanppl.lokasippl', 'mahasiswa.penempatanpkl.lokasipkl', 'mahasiswa.penempatanmagang.lokasimagang', 'mahasiswa.activeKegiatan'])
            ->whereHas('mahasiswa', function ($q) use ($selectedTA, $selectedKegiatan) {
                if ($selectedTA) {
                    $q->withTahunAkademik($selectedTA);
                }
                if ($selectedKegiatan) {
                    $q->withKegiatan($selectedKegiatan);
                }
            });

        $mahasiswaUjian = $query->get();

        return view('dosen.ujian_index', compact('mahasiswaUjian', 'tahunAkademiks', 'selectedTA', 'selectedKegiatan'));
    }

    public function detailMahasiswa($nim)
    {
        $dosen = Auth::guard('dosen')->user();

        $isUjian = DosenPenguji::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::with([
            'penempatankkn.lokasikkn', 'penempatanppl.lokasippl',
            'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang',
            'publikasis', 'activeKegiatan',
        ])->where('nim', $nim)->firstOrFail();

        $jurnals = Jurnal::where('nim', $nim)->orderBy('tanggal', 'desc')->get();
        $bimbingans = \App\Models\Bimbingan::where('nim', $nim)->orderBy('tanggal_bimbingan', 'desc')->get();

        return view('dosen.ujian_detail', compact('mahasiswa', 'jurnals', 'bimbingans', 'isUjian'));
    }

    public function inputNilai(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();

        $ujian = DosenPenguji::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::with('activeKegiatan')->where('nim', $nim)->firstOrFail();

        if ($mahasiswa->kegiatan === 'PPL') {
            $request->validate([
                'nilai' => 'required|numeric|min:0|max:100',
                'catatan' => 'nullable|string|max:5000',
            ], [
                'nilai.required' => 'Nilai ujian laporan wajib diisi.',
                'nilai.numeric' => 'Nilai harus berupa angka.',
                'nilai.min' => 'Nilai minimal adalah 0.',
                'nilai.max' => 'Nilai maksimal adalah 100.',
            ]);

            $ujian->update([
                'nilai' => $request->nilai,
                'catatan' => $request->input('catatan'),
            ]);
        } else {
            $request->validate([
                'nilai_keterlaksanaan' => 'required|numeric|min:0|max:100',
                'nilai_kontribusi' => 'required|numeric|min:0|max:100',
                'nilai_kerjasama' => 'required|numeric|min:0|max:100',
                'nilai_kreativitas' => 'required|numeric|min:0|max:100',
                'nilai_partisipasi' => 'required|numeric|min:0|max:100',
                'catatan' => 'nullable|string|max:5000',
            ], [
                'nilai_keterlaksanaan.required' => 'Nilai program kerja (Prog) wajib diisi.',
                'nilai_kontribusi.required' => 'Nilai kontribusi (Kont) wajib diisi.',
                'nilai_kerjasama.required' => 'Nilai kerjasama tim (Tim) wajib diisi.',
                'nilai_kreativitas.required' => 'Nilai kreativitas (Kreat) wajib diisi.',
                'nilai_partisipasi.required' => 'Nilai etika/partisipasi (Etika) wajib diisi.',
            ]);

            $nilaiRata = round(($request->nilai_keterlaksanaan + $request->nilai_kontribusi + $request->nilai_kerjasama + $request->nilai_kreativitas + $request->nilai_partisipasi) / 5, 1);

            $ujian->update([
                'nilai_keterlaksanaan' => $request->nilai_keterlaksanaan,
                'nilai_kontribusi' => $request->nilai_kontribusi,
                'nilai_kerjasama' => $request->nilai_kerjasama,
                'nilai_kreativitas' => $request->nilai_kreativitas,
                'nilai_partisipasi' => $request->nilai_partisipasi,
                'nilai' => $nilaiRata,
                'catatan' => $request->input('catatan'),
            ]);
        }

        // Kirim notifikasi ke mahasiswa
        \App\Models\Notifikasi::kirim(
            $nim,
            'Nilai & Catatan Revisi Ujian',
            "Dosen Penguji ({$dosen->nama}) telah menginput/memperbarui nilai dan catatan revisi ujian akhir Anda.",
            'info'
        );

        return redirect()->back()->with('success', 'Nilai dan catatan/revisi ujian berhasil disimpan!');
    }
}
