<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publikasi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PublikasiController extends Controller
{
    public function index()
    {
        
        $publikasis = Publikasi::where('nim', auth()->user()->nim)->get();
        return view('mahasiswa.publikasi.index', compact('publikasis'));
    }

    public function create()
    {
        return view('mahasiswa.publikasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'link' => 'required|string|max:255', // Maks 2MB
        ]);


        Publikasi::create([
            'nim' =>Auth::user()->nim,
            'judul' => $request->judul,
            'link' => $request->link,
        ]);

        return redirect()->route('publikasi.index')->with('success', 'Publikasi berhasil diupload.');
    }


    public function destroy($id)
    {
        $publikasi = Publikasi::findOrFail($id);
        $publikasi->delete();

        return redirect()->route('publikasi.index')->with('success', 'Publikasi berhasil dihapus.');
    }
}
