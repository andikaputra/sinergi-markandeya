<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimbinganMahasiswaController extends Controller
{

    public function dashboard()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->loadMissing([
            'activeKegiatan',
            'penempatankkn.lokasikkn',
            'penempatanppl.lokasippl',
            'penempatanpkl.lokasipkl',
            'penempatanmagang.lokasimagang',
            'dosenPembimbing.dosen'
        ]);

        $bimbingans = Bimbingan::where('nim', $mahasiswa->nim)
            ->with('dosenPembimbing.dosen')
            ->orderBy('tanggal_bimbingan', 'desc')
            ->paginate(10);

        $dosenPembimbing = $mahasiswa->dosenPembimbing;

        $statistik = [
            'total' => Bimbingan::where('nim', $mahasiswa->nim)->count(),
            'disetujui' => Bimbingan::where('nim', $mahasiswa->nim)->where('status', 'disetujui')->count(),
            'perlu_revisi' => Bimbingan::where('nim', $mahasiswa->nim)->where('status', 'perlu_revisi')->count(),
            'belum_direview' => Bimbingan::where('nim', $mahasiswa->nim)->where('status', 'belum_direview')->count(),
        ];

        return view('mahasiswa.bimbingan.dashboard', compact('mahasiswa', 'bimbingans', 'dosenPembimbing', 'statistik'));
    }

    public function create()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->loadMissing(['activeKegiatan', 'dosenPembimbing.dosen']);
        $dosenPembimbing = $mahasiswa->dosenPembimbing;

        if (!$dosenPembimbing) {
            return redirect()->route('bimbingan.dashboard')->with('error', 'Anda belum memiliki dosen pembimbing. Silakan hubungi admin prodi.');
        }

        return view('mahasiswa.bimbingan.create', compact('mahasiswa', 'dosenPembimbing'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $validated = $request->validate([
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_bimbingan' => 'required|date',
            'materi_terlampir' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
        ], [
            'topik.required' => 'Topik bimbingan wajib diisi',
            'deskripsi.required' => 'Deskripsi / pokok bahasan bimbingan wajib diisi',
            'tanggal_bimbingan.required' => 'Tanggal bimbingan wajib ditentukan',
            'materi_terlampir.max' => 'Ukuran berkas maksimal 10MB',
        ]);

        $dosenPembimbing = DosenPembimbing::where('nim', $mahasiswa->nim)->first();

        if (!$dosenPembimbing) {
            return back()->with('error', 'Dosen pembimbing tidak ditemukan.');
        }

        if ($request->hasFile('materi_terlampir')) {
            $file = $request->file('materi_terlampir');
            $filename = 'bimbingan_' . $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('bimbingan', $filename, 'public');
            $validated['materi_terlampir'] = $filename;
        }

        $bimbingan = Bimbingan::create([
            'nim' => $mahasiswa->nim,
            'dosen_pembimbing_id' => $dosenPembimbing->id,
            'topik' => $validated['topik'],
            'deskripsi' => $validated['deskripsi'],
            'tanggal_bimbingan' => $validated['tanggal_bimbingan'],
            'materi_terlampir' => $validated['materi_terlampir'] ?? null,
            'status' => 'belum_direview',
        ]);

        return redirect()->route('bimbingan.dashboard')->with('success', 'Permohonan bimbingan berhasil diajukan! Menunggu review dari dosen pembimbing.');
    }

    public function show(Bimbingan $bimbingan)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($bimbingan->nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized');
        }

        $bimbingan->load('dosenPembimbing.dosen');
        $mahasiswa->loadMissing(['activeKegiatan', 'dosenPembimbing.dosen']);

        return view('mahasiswa.bimbingan.show', compact('mahasiswa', 'bimbingan'));
    }

    public function edit(Bimbingan $bimbingan)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($bimbingan->nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized');
        }

        $bimbingan->load('dosenPembimbing.dosen');
        $mahasiswa->loadMissing(['activeKegiatan', 'dosenPembimbing.dosen']);
        $dosenPembimbing = $mahasiswa->dosenPembimbing;

        return view('mahasiswa.bimbingan.edit', compact('mahasiswa', 'bimbingan', 'dosenPembimbing'));
    }

    public function update(Request $request, Bimbingan $bimbingan)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($bimbingan->nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_bimbingan' => 'required|date',
            'materi_terlampir' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
        ], [
            'topik.required' => 'Topik bimbingan wajib diisi',
            'deskripsi.required' => 'Deskripsi / pokok bahasan perbaikan wajib diisi',
            'tanggal_bimbingan.required' => 'Tanggal bimbingan wajib ditentukan',
            'materi_terlampir.max' => 'Ukuran berkas maksimal 10MB',
        ]);

        $updateData = [
            'topik' => $validated['topik'],
            'deskripsi' => $validated['deskripsi'],
            'tanggal_bimbingan' => $validated['tanggal_bimbingan'],
            'status' => 'belum_direview', // Reset status agar dosen dapat me-review kembali revisi ini
        ];

        if ($request->hasFile('materi_terlampir')) {
            $file = $request->file('materi_terlampir');
            $filename = 'bimbingan_revisi_' . $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('bimbingan', $filename, 'public');
            $updateData['materi_terlampir'] = $filename;
        }

        $bimbingan->update($updateData);

        return redirect()->route('bimbingan.show', $bimbingan->id)->with('success', 'Perbaikan bimbingan berhasil dikirimkan! Status kembali menjadi Menunggu Review Dosen.');
    }

    public function cetak()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->loadMissing([
            'activeKegiatan',
            'penempatankkn.lokasikkn',
            'penempatanppl.lokasippl',
            'penempatanpkl.lokasipkl',
            'penempatanmagang.lokasimagang',
            'dosenPembimbing.dosen'
        ]);

        $bimbingans = Bimbingan::where('nim', $mahasiswa->nim)
            ->with('dosenPembimbing.dosen')
            ->orderBy('tanggal_bimbingan', 'asc')
            ->get();

        $dosenPembimbing = $mahasiswa->dosenPembimbing;

        return view('mahasiswa.bimbingan.cetak', compact('mahasiswa', 'bimbingans', 'dosenPembimbing'));
    }
}
