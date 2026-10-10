<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanLokasiPKL;
use App\Models\LokasiPkl;
use App\Models\PenempatanPkl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Mahasiswa;

class PengajuanLokasiPKLController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanLokasiPKL::where('nim', Auth::user()->nim)->get();
        return view('mahasiswa.pengajuanpkl.index', compact('pengajuans'));
    }

    public function adminindex()
    {
        $pengajuans = PengajuanLokasiPKL::with('mahasiswa')->get();
        return view('admin.pengajuanpkl', compact('pengajuans'));
    }

    public function create()
    {
        return view('mahasiswa.pengajuanpkl.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
        ]);

        $mahasiswa = Auth::guard('mahasiswa')->user();
        abort_unless($mahasiswa->activeKegiatan?->kegiatan === 'PKL' && $mahasiswa->activeKegiatan?->status_kegiatan === 'aktif', 403, 'Anda tidak sedang mengikuti kegiatan PKL.');

        PengajuanLokasiPKL::create([
            'nim' => $mahasiswa->nim,
            'tahun_akademik' => $mahasiswa->tahun_akademik ?? '',
            'nama_instansi' => $request->nama_instansi,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'status' => 'pending',
        ]);

        return redirect()->route('pengajuanpkl.index')->with('success', 'Pengajuan Lokasi PKL berhasil dikirim!');
    }

    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            $pengajuan = PengajuanLokasiPKL::lockForUpdate()->findOrFail($id);
            if ($pengajuan->status !== 'pending') {
                throw ValidationException::withMessages(['pengajuan' => 'Pengajuan ini sudah diproses.']);
            }

            $mahasiswa = Mahasiswa::where('nim', $pengajuan->nim)->lockForUpdate()->firstOrFail();
            $period = $pengajuan->tahun_akademik;
            $registration = $mahasiswa->mahasiswaKegiatan()->where('kegiatan', 'PKL')
                ->where('tahun_akademik', $period === '' ? null : $period)
                ->where('status_kegiatan', 'aktif')->exists();
            if (!$registration) {
                throw ValidationException::withMessages(['pengajuan' => 'Pendaftaran kegiatan untuk pengajuan ini tidak aktif.']);
            }

            $lokasi = LokasiPkl::firstOrCreate(
                ['nama_instansi' => $pengajuan->nama_instansi, 'alamat' => $pengajuan->alamat],
                ['kontak' => $pengajuan->kontak]
            );
            $lokasi = LokasiPkl::lockForUpdate()->findOrFail($lokasi->id);
            $placements = PenempatanPkl::withoutGlobalScope('periode_aktif')
                ->where('tahun_akademik', $period);
            $alreadyPlaced = (clone $placements)->where('nim', $pengajuan->nim)
                ->where('lokasi_pkl_id', $lokasi->id)->exists();
            if (!$alreadyPlaced && $lokasi->maks_peserta !== null
                && (clone $placements)->where('lokasi_pkl_id', $lokasi->id)->count() >= $lokasi->maks_peserta) {
                throw ValidationException::withMessages(['pengajuan' => 'Kapasitas lokasi sudah penuh.']);
            }

            PenempatanPkl::withoutGlobalScope('periode_aktif')->updateOrCreate(
                ['nim' => $pengajuan->nim, 'tahun_akademik' => $period],
                ['lokasi_pkl_id' => $lokasi->id]
            );
            $pengajuan->update(['status' => 'approved']);
        });

        return back()->with('success', 'Pengajuan PKL disetujui dan penempatan mahasiswa diperbarui.');
    }

    public function reject($id)
    {
        DB::transaction(function () use ($id) {
            $pengajuan = PengajuanLokasiPKL::lockForUpdate()->findOrFail($id);
            if ($pengajuan->status !== 'pending') {
                throw ValidationException::withMessages(['pengajuan' => 'Pengajuan ini sudah diproses.']);
            }
            $pengajuan->update(['status' => 'rejected']);
        });

        return back()->with('success', 'Pengajuan PKL ditolak.');
    }
}
