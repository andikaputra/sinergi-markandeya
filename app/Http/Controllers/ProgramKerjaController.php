<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerja;
use App\Models\Luaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:mahasiswa');
    }

    public function index()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $programKerjas = ProgramKerja::where('nim', $mahasiswa->nim)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statistik = [
            'total' => ProgramKerja::where('nim', $mahasiswa->nim)->count(),
            'rencana' => ProgramKerja::where('nim', $mahasiswa->nim)->where('status', 'rencana')->count(),
            'sedang_berjalan' => ProgramKerja::where('nim', $mahasiswa->nim)->where('status', 'sedang_berjalan')->count(),
            'selesai' => ProgramKerja::where('nim', $mahasiswa->nim)->where('status', 'selesai')->count(),
        ];

        return view('mahasiswa.program-kerja.index', compact('programKerjas', 'statistik'));
    }

    public function create()
    {
        return view('mahasiswa.program-kerja.create');
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
        ]);

        ProgramKerja::create(array_merge($validated, [
            'nim' => $mahasiswa->nim,
            'status' => 'rencana',
        ]));

        return redirect()->route('program-kerja.index')->with('success', 'Program kerja berhasil dibuat');
    }

    public function show(ProgramKerja $programKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($programKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $luarans = $programKerja->luarans()->orderBy('created_at', 'desc')->get();
        $statistikLuaran = [
            'total' => $luarans->count(),
            'selesai' => $luarans->where('status', 'selesai')->count(),
        ];

        return view('mahasiswa.program-kerja.show', compact('programKerja', 'luarans', 'statistikLuaran'));
    }

    public function edit(ProgramKerja $programKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($programKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        return view('mahasiswa.program-kerja.edit', compact('programKerja'));
    }

    public function update(Request $request, ProgramKerja $programKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($programKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:rencana,sedang_berjalan,selesai,tunda',
        ]);

        $programKerja->update($validated);

        return redirect()->route('program-kerja.show', $programKerja)->with('success', 'Program kerja berhasil diupdate');
    }

    public function destroy(ProgramKerja $programKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($programKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $programKerja->delete();

        return redirect()->route('program-kerja.index')->with('success', 'Program kerja berhasil dihapus');
    }

    // Luaran methods
    public function storeLuaran(Request $request, ProgramKerja $programKerja)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($programKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tipe' => 'required|string|max:100',
            'tanggal_selesai' => 'required|date',
            'file_path' => 'nullable|url:http,https',
        ]);

        $programKerja->luarans()->create($validated);

        return back()->with('success', 'Luaran berhasil ditambahkan');
    }

    public function updateLuaranStatus(Request $request, Luaran $luaran)
    {
        $validated = $request->validate([
            'status' => 'required|in:belum_dikerjakan,sedang_dikerjakan,selesai',
            'persentase_selesai' => 'required|integer|min:0|max:100',
        ]);

        $luaran->update($validated);

        return back()->with('success', 'Status luaran berhasil diupdate');
    }

    public function deleteLuaran(Luaran $luaran)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($luaran->programKerja->nim !== $mahasiswa->nim) {
            abort(403);
        }

        $luaran->delete();

        return back()->with('success', 'Luaran berhasil dihapus');
    }
}
