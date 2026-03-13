<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanLokasiMagang;
use App\Models\LokasiMagang;
use App\Models\PenempatanMagang;
use Illuminate\Support\Facades\Auth;

class PengajuanLokasiMagangController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanLokasiMagang::where('nim', Auth::user()->nim)->get();
        return view('mahasiswa.pengajuanmagang.index', compact('pengajuans'));
    }

    public function adminindex()
    {
        $pengajuans = PengajuanLokasiMagang::with('mahasiswa')->get();
        return view('admin.pengajuanmagang', compact('pengajuans'));
    }

    public function create()
    {
        return view('mahasiswa.pengajuanmagang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required',
            'alamat' => 'required',
            'kontak' => 'nullable|string',
        ]);

        PengajuanLokasiMagang::create([
            'nim' => Auth::user()->nim,
            'nama_instansi' => $request->nama_instansi,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'status' => 'pending',
        ]);

        return redirect()->route('pengajuanmagang.index')->with('success', 'Pengajuan Lokasi Magang berhasil dikirim!');
    }

    public function approve($id)
    {
        $pengajuan = PengajuanLokasiMagang::findOrFail($id);
        $pengajuan->update(['status' => 'approved']);

        // Auto-add instansi to master lokasi magang
        $lokasi = LokasiMagang::firstOrCreate(
            ['nama_instansi' => $pengajuan->nama_instansi],
            ['alamat' => $pengajuan->alamat, 'kontak' => $pengajuan->kontak]
        );

        // Auto-place student at the location
        PenempatanMagang::firstOrCreate(
            ['nim' => $pengajuan->nim],
            ['lokasi_magang_id' => $lokasi->id]
        );

        return redirect()->back()->with('success', 'Pengajuan Magang disetujui dan mahasiswa otomatis ditempatkan.');
    }

    public function reject($id)
    {
        $pengajuan = PengajuanLokasiMagang::findOrFail($id);
        $pengajuan->update(['status' => 'rejected']);
        return redirect()->back()->with('error', 'Pengajuan Magang ditolak.');
    }
}
