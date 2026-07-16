<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimbinganMahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:mahasiswa');
    }

    public function dashboard()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $bimbingans = Bimbingan::where('nim', $mahasiswa->nim)
            ->with('dosenPembimbing.dosen')
            ->orderBy('tanggal_bimbingan', 'desc')
            ->paginate(10);

        $dosenPembimbing = DosenPembimbing::where('nim', $mahasiswa->nim)->first();

        $statistik = [
            'total' => Bimbingan::where('nim', $mahasiswa->nim)->count(),
            'disetujui' => Bimbingan::where('nim', $mahasiswa->nim)->where('status', 'disetujui')->count(),
            'perlu_revisi' => Bimbingan::where('nim', $mahasiswa->nim)->where('status', 'perlu_revisi')->count(),
            'belum_direview' => Bimbingan::where('nim', $mahasiswa->nim)->where('status', 'belum_direview')->count(),
        ];

        return view('mahasiswa.bimbingan.dashboard', compact('bimbingans', 'dosenPembimbing', 'statistik'));
    }

    public function create()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $dosenPembimbing = DosenPembimbing::where('nim', $mahasiswa->nim)->first();

        if (!$dosenPembimbing) {
            return redirect()->route('bimbingan.dashboard')->with('error', 'Anda belum memiliki dosen pembimbing');
        }

        return view('mahasiswa.bimbingan.create', compact('dosenPembimbing'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $validated = $request->validate([
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_bimbingan' => 'required|date|after_or_equal:today',
            'materi_terlampir' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $dosenPembimbing = DosenPembimbing::where('nim', $mahasiswa->nim)->first();

        if (!$dosenPembimbing) {
            return back()->with('error', 'Dosen pembimbing tidak ditemukan');
        }

        if ($request->hasFile('materi_terlampir')) {
            $file = $request->file('materi_terlampir');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('bimbingan', $filename, 'public');
            $validated['materi_terlampir'] = $filename;
        }

        Bimbingan::create([
            'nim' => $mahasiswa->nim,
            'dosen_pembimbing_id' => $dosenPembimbing->id,
            'topik' => $validated['topik'],
            'deskripsi' => $validated['deskripsi'],
            'tanggal_bimbingan' => $validated['tanggal_bimbingan'],
            'materi_terlampir' => $validated['materi_terlampir'] ?? null,
            'status' => 'belum_direview',
        ]);

        return redirect()->route('bimbingan.dashboard')->with('success', 'Permohonan bimbingan berhasil dibuat');
    }

    public function show(Bimbingan $bimbingan)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($bimbingan->nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized');
        }

        $bimbingan->load('dosenPembimbing.dosen');

        return view('mahasiswa.bimbingan.show', compact('bimbingan'));
    }
}
