<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class AdminController extends Controller
{
    public function pesertaKKN()
    {
        $pesertaKKN = Mahasiswa::where('kegiatan', 'KKN')
        ->with('penempatankkn.lokasikkn') // Pastikan relasi sesuai dengan nama method di model
        ->get();
        return view('admin.kkn', compact('pesertaKKN'));
    }

    public function pesertaPPL()
    {
        $pesertaPPL = Mahasiswa::where('kegiatan', 'PPL')
        ->with('penempatanppl.lokasippl') // Pastikan relasi sesuai dengan nama method di model
        ->get();
    
    return view('admin.ppl', compact('pesertaPPL'));
    }

    public function pesertaPKL()
    {
        $pesertaPKL = Mahasiswa::where('kegiatan', 'PKL')->get();
        return view('admin.pkl', compact('pesertaPKL'));
        
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
            'nidn' => 'required|string',
            'nama' => 'required|string',
        ]);

        Dosen::create([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'password' => $request->nidn,
        ]);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

}
