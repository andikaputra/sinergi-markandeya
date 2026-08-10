<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\MahasiswaKegiatan;
use App\Models\Dosen;
use App\Models\User;
use App\Models\PenempatanKkn;
use App\Models\PenempatanPpl;
use App\Models\PenempatanPkl;
use App\Models\PenempatanMagang;
use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\TahunAkademik;
use App\Models\Publikasi;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahKKN    = Mahasiswa::withKegiatan('KKN')->count();
        $jumlahPPL    = Mahasiswa::withKegiatan('PPL')->count();
        $jumlahPKL    = Mahasiswa::withKegiatan('PKL')->count();
        $jumlahMagang = Mahasiswa::withKegiatan('Magang')->count();

        // Akun nonaktif
        $jumlahPending = Mahasiswa::where('status', 'nonaktif')->count();

        // Belum dapat penempatan (sudah daftar tapi belum ada di tabel penempatan)
        $belumPenempatanKKN    = Mahasiswa::withKegiatan('KKN')->whereDoesntHave('penempatankkn')->count();
        $belumPenempatanPPL    = Mahasiswa::withKegiatan('PPL')->whereDoesntHave('penempatanppl')->count();
        $belumPenempatanPKL    = Mahasiswa::withKegiatan('PKL')->whereDoesntHave('penempatanpkl')->count();
        $belumPenempatanMagang = Mahasiswa::withKegiatan('Magang')->whereDoesntHave('penempatanmagang')->count();

        // Mahasiswa pending terbaru (5)
        $pendingTerbaru = Mahasiswa::where('status', 'pending')->latest()->limit(5)->get();

        // Pendaftaran kegiatan terbaru (10)
        $pendaftaranTerbaru = \App\Models\MahasiswaKegiatan::with('mahasiswa')
            ->latest()->limit(10)->get();

        // Pengumuman aktif
        $jumlahPengumuman = \App\Models\Pengumuman::where('is_published', true)->count();

        // TA aktif
        $activeTA = TahunAkademik::active();

        return view('admin.admindashboard', compact(
            'jumlahKKN', 'jumlahPPL', 'jumlahPKL', 'jumlahMagang',
            'jumlahPending', 'belumPenempatanKKN', 'belumPenempatanPPL',
            'belumPenempatanPKL', 'belumPenempatanMagang',
            'pendingTerbaru', 'pendaftaranTerbaru', 'jumlahPengumuman', 'activeTA'
        ));
    }

    public function pesertaKKN(Request $request)
    {
        $query = Mahasiswa::withKegiatan('KKN')->with(['penempatankkn.lokasikkn', 'publikasis', 'dosenPembimbing.dosen', 'dosenPenguji.dosen', 'pembimbingLuarMahasiswa.pembimbingLuar', 'dosenPenilaiPublikasi.dosen', 'activeKegiatan']);

        if ($request->has('ta') && $request->ta != '') {
            $query->withKegiatanAndTA('KKN', $request->ta);
        }

        $pesertaKKN = $query->orderBy('kecamatan', 'asc')->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();

        return view('admin.kkn', compact('pesertaKKN', 'tahunAkademiks'));
    }

    public function pesertaPPL(Request $request)
    {
        $query = Mahasiswa::withKegiatan('PPL')->with(['penempatanppl.lokasippl', 'publikasis', 'dosenPembimbing.dosen', 'dosenPenguji.dosen', 'pembimbingLuarMahasiswa.pembimbingLuar', 'dosenPenilaiPublikasi.dosen', 'activeKegiatan']);

        if ($request->has('ta') && $request->ta != '') {
            $query->withKegiatanAndTA('PPL', $request->ta);
        }

        $pesertaPPL = $query->orderBy('kecamatan', 'asc')->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();

        return view('admin.ppl', compact('pesertaPPL', 'tahunAkademiks'));
    }

    public function pesertaPKL(Request $request)
    {
        $query = Mahasiswa::withKegiatan('PKL')->with(['penempatanpkl.lokasipkl', 'publikasis', 'dosenPembimbing.dosen', 'dosenPenguji.dosen', 'pembimbingLuarMahasiswa.pembimbingLuar', 'dosenPenilaiPublikasi.dosen', 'activeKegiatan']);

        if ($request->has('ta') && $request->ta != '') {
            $query->withKegiatanAndTA('PKL', $request->ta);
        }

        $pesertaPKL = $query->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();

        return view('admin.pkl', compact('pesertaPKL', 'tahunAkademiks'));
    }

    public function pesertaMagang(Request $request)
    {
        $query = Mahasiswa::withKegiatan('Magang')->with(['penempatanmagang.lokasimagang', 'publikasis', 'dosenPembimbing.dosen', 'dosenPenguji.dosen', 'pembimbingLuarMahasiswa.pembimbingLuar', 'dosenPenilaiPublikasi.dosen', 'activeKegiatan']);

        if ($request->has('ta') && $request->ta != '') {
            $query->withKegiatanAndTA('Magang', $request->ta);
        }

        $pesertaMagang = $query->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();

        return view('admin.magang', compact('pesertaMagang', 'tahunAkademiks'));
    }

    // Magang Placement Methods
    public function assignMagangIndex()
    {
        $mahasiswas = Mahasiswa::withKegiatan('Magang')->whereDoesntHave('penempatanmagang')->get();
        $lokasimagangs = \App\Models\LokasiMagang::all();
        $assignments = PenempatanMagang::with(['mahasiswa', 'lokasimagang'])->get();

        return view('admin.assignlokasimagang', compact('mahasiswas', 'lokasimagangs', 'assignments'));
    }

    public function assignMagangStore(Request $request)
    {
        $request->validate([
            'nims' => 'required|array',
            'lokasimagang' => 'required|exists:lokasi_magangs,id'
        ]);

        foreach ($request->nims as $nim) {
            PenempatanMagang::updateOrCreate(
                ['nim' => $nim],
                ['lokasi_magang_id' => $request->lokasimagang]
            );
        }

        return redirect()->back()->with('success', 'Penempatan Magang berhasil diperbarui!');
    }

    public function assignMagangDelete($id)
    {
        PenempatanMagang::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penempatan Magang dihapus.');
    }

    public function indexdosen()
    {
        $dosens = Dosen::all();
        return view('admin.dosen', compact('dosens'));
    }

    public function createdosen()
    {
        return view('admin.tambahdosen');
    }

    public function storedosen(Request $request)
    {
        $request->validate([
            'nidn' => 'required|string|unique:dosens,nidn',
            'nama' => 'required|string',
        ]);

        Dosen::create([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'password' => $request->nidn,
        ]);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    // Manual Student Creation
    public function createMahasiswa()
    {
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();
        return view('admin.mahasiswa.create', compact('tahunAkademiks'));
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'nim'   => 'required|string|max:20|unique:mahasiswas,nim',
            'prodi' => 'required|in:PGSD,PBSI,PBI,SI,ME,PARBUD,HUKUM',
        ]);

        Mahasiswa::create([
            'nama'     => $request->nama,
            'nim'      => $request->nim,
            'prodi'    => $request->prodi,
            'password' => $request->nim,
            'status'   => 'aktif',
        ]);

        return redirect()->route('admin.mahasiswa.pending')
            ->with('success', 'Mahasiswa ' . $request->nama . ' berhasil ditambahkan. Password default: ' . $request->nim);
    }

    /**
     * Admin: Assign kegiatan ke mahasiswa
     */
    public function assignKegiatan(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:mahasiswas,nim',
            'kegiatan' => 'required|in:KKN,PPL,PKL,Magang',
            'tahun_akademik' => 'required|string',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->firstOrFail();

        // Cek duplikasi
        $exists = MahasiswaKegiatan::where('nim', $request->nim)
            ->where('kegiatan', $request->kegiatan)
            ->where('tahun_akademik', $request->tahun_akademik)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Mahasiswa sudah terdaftar di kegiatan ini untuk tahun akademik tersebut.');
        }

        $mahasiswa->addKegiatan($request->kegiatan, $request->tahun_akademik);

        // Dual-write
        $mahasiswa->update([
            'kegiatan' => $request->kegiatan,
            'tahun_akademik' => $request->tahun_akademik,
        ]);

        return redirect()->back()->with('success', 'Kegiatan berhasil di-assign ke mahasiswa.');
    }

    // Fitur Import Mahasiswa CSV
    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048'
        ]);

        $validProdi = ['PGSD', 'PBSI', 'PBI', 'SI', 'ME', 'PARBUD', 'HUKUM'];
        $validKegiatan = ['KKN', 'PPL', 'PKL', 'Magang'];

        $file = $request->file('file_csv');
        $handle = fopen($file->getPathname(), 'r');

        // Skip header
        fgetcsv($handle);

        $count = 0;
        $errors = [];
        $row = 1;
        $activeTA = TahunAkademik::active();
        $taString = $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null;

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                if (count($data) < 5) {
                    $errors[] = "Baris $row: kolom kurang dari 5";
                    continue;
                }

                $nama = trim($data[0]);
                $nim = trim($data[1]);
                $email = trim($data[2]);
                $prodi = trim($data[3]);
                $kegiatan = trim($data[4]);

                if (empty($nim) || empty($nama)) {
                    $errors[] = "Baris $row: NIM atau Nama kosong";
                    continue;
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris $row ($nim): email tidak valid";
                    continue;
                }
                if (!in_array($prodi, $validProdi)) {
                    $errors[] = "Baris $row ($nim): prodi '$prodi' tidak valid";
                    continue;
                }
                if (!in_array($kegiatan, $validKegiatan)) {
                    $errors[] = "Baris $row ($nim): kegiatan '$kegiatan' tidak valid";
                    continue;
                }

                $mahasiswa = Mahasiswa::updateOrCreate(
                    ['nim' => $nim],
                    [
                        'nama'           => $nama,
                        'email'          => $email,
                        'prodi'          => $prodi,
                        'kegiatan'       => $kegiatan,
                        'kecamatan'      => trim($data[5] ?? '-') ?: '-',
                        'kampus'         => trim($data[6] ?? 'Markandeya') ?: 'Markandeya',
                        'password'       => $nim,
                        'tahun_akademik' => $taString,
                        'pembayaranKRS'  => '-',
                        'KRS'            => '-',
                        'status'         => 'aktif',
                    ]
                );

                // Dual-write: tulis juga ke pivot
                MahasiswaKegiatan::updateOrCreate(
                    ['nim' => $nim, 'kegiatan' => $kegiatan, 'tahun_akademik' => $taString],
                    ['is_active' => true]
                );

                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
        fclose($handle);

        $message = "Berhasil mengimpor $count mahasiswa!";
        if (!empty($errors)) {
            $message .= ' (' . count($errors) . ' baris dilewati: ' . implode('; ', array_slice($errors, 0, 5)) . ')';
        }
        return redirect()->back()->with('success', $message);
    }

    public function importDosen(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048'
        ]);

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
                if (count($data) < 2) {
                    $errors[] = "Baris $row: kolom kurang dari 2";
                    continue;
                }

                $nidn = trim($data[0]);
                $nama = trim($data[1]);

                if (empty($nidn) || empty($nama)) {
                    $errors[] = "Baris $row: NIDN atau Nama kosong";
                    continue;
                }

                Dosen::updateOrCreate(
                    ['nidn' => $nidn],
                    [
                        'nama' => $nama,
                        'password' => $nidn,
                    ]
                );
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Gagal mengimpor dosen: ' . $e->getMessage());
        }
        fclose($handle);

        $message = "Berhasil mengimpor $count dosen!";
        if (!empty($errors)) {
            $message .= ' (' . count($errors) . ' baris dilewati: ' . implode('; ', array_slice($errors, 0, 5)) . ')';
        }
        return redirect()->back()->with('success', $message);
    }

    // Fitur Export CSV (KKN, PPL, PKL, Magang)
    public function exportKKN(Request $request)
    {
        return $this->generateCsvExport($request, 'KKN');
    }

    public function exportPPL(Request $request)
    {
        return $this->generateCsvExport($request, 'PPL');
    }

    public function exportPKL(Request $request)
    {
        return $this->generateCsvExport($request, 'PKL');
    }

    public function exportMagang(Request $request)
    {
        return $this->generateCsvExport($request, 'Magang');
    }

    private function generateCsvExport(Request $request, $kegiatan)
    {
        $fileName = 'rekap_peserta_' . strtolower($kegiatan) . '_' . date('Y-m-d') . '.csv';

        $placementRelation = match ($kegiatan) {
            'KKN' => 'penempatankkn.lokasikkn',
            'PPL' => 'penempatanppl.lokasippl',
            'PKL' => 'penempatanpkl.lokasipkl',
            'Magang' => 'penempatanmagang.lokasimagang',
        };

        $query = Mahasiswa::withKegiatan($kegiatan)
            ->with([$placementRelation, 'dosenPembimbing.dosen', 'dosenPenguji.dosen']);

        if ($request->has('ta') && $request->ta != '') {
            $query->withKegiatanAndTA($kegiatan, $request->ta);
        }
        $tasks = $query->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('NIM', 'Nama Mahasiswa', 'Prodi', 'Lokasi', 'Dosen Pembimbing', 'Nilai DP', 'Dosen Penguji', 'Nilai DU', 'Nilai Akhir');

        $callback = function() use($tasks, $columns, $kegiatan) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $lokasi = '-';
                if ($kegiatan == 'KKN') $lokasi = $task->penempatankkn?->lokasikkn?->desa;
                elseif ($kegiatan == 'PPL') $lokasi = $task->penempatanppl?->lokasippl?->Sekolah;
                elseif ($kegiatan == 'PKL') $lokasi = $task->penempatanpkl?->lokasipkl?->nama_instansi;
                elseif ($kegiatan == 'Magang') $lokasi = $task->penempatanmagang?->lokasimagang?->nama_instansi;

                fputcsv($file, array(
                    $task->nim,
                    $task->nama,
                    $task->prodi_full,
                    $lokasi ?? '-',
                    $task->dosenPembimbing?->dosen?->nama ?? '-',
                    $task->dosenPembimbing?->nilai ?? '-',
                    $task->dosenPenguji?->dosen?->nama ?? '-',
                    $task->dosenPenguji?->nilai ?? '-',
                    $task->nilai_akhir,
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // PDF / Print Reports
    public function printKKN(Request $request) { return $this->viewPrint($request, 'KKN'); }
    public function printPPL(Request $request) { return $this->viewPrint($request, 'PPL'); }
    public function printPKL(Request $request) { return $this->viewPrint($request, 'PKL'); }
    public function printMagang(Request $request) { return $this->viewPrint($request, 'Magang'); }

    private function viewPrint(Request $request, $kegiatan)
    {
        $query = Mahasiswa::withKegiatan($kegiatan)->with(['dosenPembimbing.dosen', 'dosenPenguji.dosen']);
        if ($request->has('ta') && $request->ta != '') {
            $query->withKegiatanAndTA($kegiatan, $request->ta);
        }
        $peserta = $query->get();
        $title = "REKAPITULASI PESERTA " . $kegiatan;
        return view('admin.export.pdf_rekap', compact('peserta', 'title'));
    }

    // ==================== ADMIN MANAGEMENT (SUPERADMIN ONLY) ====================

    public function adminIndex()
    {
        $admins = User::orderByRaw("role = 'superadmin' DESC")->orderBy('name')->get();
        return view('admin.kelola_admin', compact('admins'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,admin',
            'kegiatan' => 'nullable|array',
            'kegiatan.*' => 'in:KKN,PPL,PKL,Magang',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'kegiatan' => $request->role === 'superadmin' ? null : ($request->kegiatan ?? []),
        ]);

        return redirect()->route('admin.kelola')->with('success', 'Admin baru berhasil ditambahkan!');
    }

    public function adminUpdate(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $rules = [
            'name'     => 'required|string|max:255',
            'role'     => 'required|in:superadmin,admin',
            'kegiatan' => 'nullable|array',
            'kegiatan.*' => 'in:KKN,PPL,PKL,Magang',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8';
        }

        $request->validate($rules);

        $data = [
            'name'     => $request->name,
            'role'     => $request->role,
            'kegiatan' => $request->role === 'superadmin' ? null : ($request->kegiatan ?? []),
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $admin->update($data);

        return redirect()->route('admin.kelola')->with('success', 'Data admin ' . $admin->name . ' berhasil diperbarui!');
    }

    public function adminDestroy($id)
    {
        $admin = User::findOrFail($id);

        if ($admin->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $admin->delete();
        return redirect()->route('admin.kelola')->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * Admin: Delete Mahasiswa (Peserta) and all related data
     */
    public function mahasiswaPending()
    {
        $mahasiswas = Mahasiswa::orderByRaw("FIELD(status,'nonaktif','aktif') ASC")
            ->orderBy('nama')->paginate(20);
        $jumlahNonaktif = Mahasiswa::where('status', 'nonaktif')->count();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();
        return view('admin.mahasiswa.pending', compact('mahasiswas', 'jumlahNonaktif', 'tahunAkademiks'));
    }

    public function approveMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update(['status' => 'aktif', 'catatan_penolakan' => null]);

        Notifikasi::kirim(
            $mahasiswa->nim,
            'Akun Diaktifkan',
            'Akun Anda telah diaktifkan oleh admin. Silakan login.',
            'sukses'
        );

        return redirect()->back()->with('success', 'Akun ' . $mahasiswa->nama . ' berhasil diaktifkan!');
    }

    public function rejectMahasiswa(Request $request, $id)
    {
        $request->validate(['catatan' => 'nullable|string|max:500']);
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update(['status' => 'nonaktif', 'catatan_penolakan' => $request->catatan]);

        Notifikasi::kirim(
            $mahasiswa->nim,
            'Akun Dinonaktifkan',
            'Akun Anda dinonaktifkan oleh admin.' . ($request->catatan ? ' Alasan: ' . $request->catatan : ''),
            'peringatan'
        );

        return redirect()->back()->with('success', 'Akun ' . $mahasiswa->nama . ' dinonaktifkan.');
    }

    public function updateStatusKegiatan(Request $request, $id)
    {
        $request->validate(['status_kegiatan' => 'required|in:aktif,selesai,dibatalkan']);
        $mk = MahasiswaKegiatan::findOrFail($id);
        $mk->update(['status_kegiatan' => $request->status_kegiatan]);

        $label = match($request->status_kegiatan) {
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default      => 'Berlangsung',
        };

        Notifikasi::kirim(
            $mk->nim,
            'Status Kegiatan Diperbarui',
            'Status kegiatan ' . $mk->kegiatan . ' (' . $mk->tahun_akademik . ') Anda diubah menjadi: ' . $label . '.',
            $request->status_kegiatan === 'selesai' ? 'sukses' : 'peringatan'
        );

        return redirect()->back()->with('success', 'Status kegiatan berhasil diperbarui.');
    }

    public function mahasiswaDestroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $nim = $mahasiswa->nim;

        DB::beginTransaction();
        try {
            // Hapus semua data terkait manual (antisipasi jika foreign key cascade tidak terpasang)
            DB::table('mahasiswa_kegiatan')->where('nim', $nim)->delete();
            DB::table('jurnals')->where('nim', $nim)->delete();
            DB::table('publikasis')->where('nim', $nim)->delete();
            DB::table('pembagian_lokasi_kkn')->where('nim', $nim)->delete();
            DB::table('Penempatan_ppl')->where('nim', $nim)->delete();
            DB::table('penempatan_pkls')->where('nim', $nim)->delete();
            DB::table('penempatan_magangs')->where('nim', $nim)->delete();
            DB::table('dosen_pembimbings')->where('nim', $nim)->delete();
            DB::table('dosen_pengujis')->where('nim', $nim)->delete();
            DB::table('pembimbing_luar_mahasiswa')->where('nim', $nim)->delete();
            DB::table('dosen_penilai_publikasis')->where('nim', $nim)->delete();
            DB::table('pengajuan_lokasi_pkl')->where('nim', $nim)->delete();
            DB::table('pengajuan_lokasi_magang')->where('nim', $nim)->delete();

            // Akhirnya hapus mahasiswa
            $mahasiswa->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Peserta ' . $mahasiswa->nama . ' berhasil dihapus beserta seluruh datanya.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus peserta: ' . $e->getMessage());
        }
    }
}
