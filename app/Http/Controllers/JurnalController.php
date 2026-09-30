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
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $jurnals = Jurnal::where('nim', $mahasiswa->nim)->orderBy('tanggal', 'desc')->get();
        return view('mahasiswa.jurnals.index', compact('jurnals'));
    }

    public function create()
    {
        return view('mahasiswa.jurnals.create');
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
        ]);

        Jurnal::create([
            'nim' => $mahasiswa->nim,
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function edit(Jurnal $jurnal)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        if ($jurnal->nim !== $mahasiswa->nim) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }

        return view('mahasiswa.jurnals.edit', compact('jurnal'));
    }

    public function update(Request $request, Jurnal $jurnal)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        if ($jurnal->nim !== $mahasiswa->nim) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
        ]);

        $jurnal->update([
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(Jurnal $jurnal)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        if ($jurnal->nim !== $mahasiswa->nim) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }

        $jurnal->delete();

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus.');
    }

    public function cetak()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $jurnals = Jurnal::where('nim', $mahasiswa->nim)->orderBy('tanggal', 'asc')->get();
        
        // Ambil data dosen pembimbing
        $pembimbing = DosenPembimbing::where('nim', $mahasiswa->nim)->with('dosen')->first();

        return view('mahasiswa.jurnals.cetak', compact('mahasiswa', 'jurnals', 'pembimbing'));
    }
}
