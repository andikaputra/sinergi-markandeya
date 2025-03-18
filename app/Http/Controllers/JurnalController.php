<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index()
    {
        // Ambil NIM mahasiswa yang login
        $nim = Auth::user()->nim;

        // Ambil jurnal yang sesuai dengan NIM
        $jurnals = Jurnal::whereHas('mahasiswa', function ($query) use ($nim) {
            $query->where('nim', $nim);
        })->orderBy('tanggal', 'desc')->get();

        return view('mahasiswa.jurnals.index', compact('jurnals'));
    }

    public function create()
    {
        return view('mahasiswa.jurnals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
        ]);

        Jurnal::create([
            'nim' => Auth::user()->nim,
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }
}
