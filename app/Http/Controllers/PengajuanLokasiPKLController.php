<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanLokasiPKL;
use Illuminate\Support\Facades\Auth;

class PengajuanLokasiPKLController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanLokasiPKL::where('nim', Auth::user()->nim)->get();
        return view('mahasiswa.pengajuanpkl.index', compact('pengajuan'));
    }
    public function adminindex()
    {
        $pengajuan = PengajuanLokasiPKL::with('mahasiswa')->get();
        return view('admin.pengajuanpkl', compact('pengajuan'));
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
        return redirect()->back()->with('success', 'Pengajuan PKL disetujui.');
    }

    public function reject($id)
    {
        $pengajuan = PengajuanLokasiPKL::findOrFail($id);
        $pengajuan->update(['status' => 'rejected']);
        return redirect()->back()->with('error', 'Pengajuan PKL ditolak.');
    }
}
