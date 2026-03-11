<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Mahasiswa;
use App\Models\DosenPembimbing;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index()
    {
        $nim = Auth::user()->nim;
        $jurnals = Jurnal::where('nim', $nim)->orderBy('tanggal', 'desc')->get();
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

    public function cetak()
    {
        $mahasiswa = Auth::user();
        $jurnals = Jurnal::where('nim', $mahasiswa->nim)->orderBy('tanggal', 'asc')->get();
        
        // Ambil data dosen pembimbing
        $pembimbing = DosenPembimbing::where('nim', $mahasiswa->nim)->with('dosen')->first();

        return view('mahasiswa.jurnals.cetak', compact('mahasiswa', 'jurnals', 'pembimbing'));
    }
}
