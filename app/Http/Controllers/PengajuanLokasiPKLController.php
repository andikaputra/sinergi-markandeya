<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanLokasiPKL;
use App\Models\LokasiPkl;
use App\Models\PenempatanPkl;
use Illuminate\Support\Facades\Auth;

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
            'nama_instansi' => 'required',
            'alamat' => 'required',
            'kontak' => 'nullable|string',
        ]);

        PengajuanLokasiPKL::create([
            'nim' => Auth::user()->nim,
            'nama_instansi' => $request->nama_instansi,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'status' => 'pending',
        ]);

        return redirect()->route('pengajuanpkl.index')->with('success', 'Pengajuan Lokasi PKL berhasil dikirim!');
    }

    public function approve($id)
    {
        $pengajuan = PengajuanLokasiPKL::findOrFail($id);
        $pengajuan->update(['status' => 'approved']);

        // Auto-add instansi to master lokasi PKL
        $lokasi = LokasiPkl::firstOrCreate(
            ['nama_instansi' => $pengajuan->nama_instansi],
            ['alamat' => $pengajuan->alamat, 'kontak' => $pengajuan->kontak]
        );

        // Auto-place student at the location
        PenempatanPkl::firstOrCreate(
            ['nim' => $pengajuan->nim],
            ['lokasi_pkl_id' => $lokasi->id]
        );

        return redirect()->back()->with('success', 'Pengajuan PKL disetujui dan mahasiswa otomatis ditempatkan.');
    }

    public function reject($id)
    {
        $pengajuan = PengajuanLokasiPKL::findOrFail($id);
        $pengajuan->update(['status' => 'rejected']);
        return redirect()->back()->with('error', 'Pengajuan PKL ditolak.');
    }
}
