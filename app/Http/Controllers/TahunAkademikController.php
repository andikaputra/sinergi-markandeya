<?php

namespace App\Http\Controllers;

use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();
        return view('admin.tahun_akademik.index', compact('tahunAkademiks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        TahunAkademik::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'is_active' => false
        ]);

        return redirect()->back()->with('success', 'Tahun Akademik berhasil ditambahkan!');
    }

    public function setActive($id)
    {
        DB::transaction(function () use ($id) {
            TahunAkademik::query()->update(['is_active' => false]);
            $tahun = TahunAkademik::findOrFail($id);
            $tahun->update(['is_active' => true]);
        });

        return redirect()->back()->with('success', 'Tahun Akademik aktif berhasil diubah!');
    }

    public function setPeriode(Request $request, $id)
    {
        $request->validate([
            'tanggal_mulai_daftar' => 'required|date',
            'tanggal_selesai_daftar' => 'required|date|after_or_equal:tanggal_mulai_daftar',
        ]);

        $tahun = TahunAkademik::findOrFail($id);
        $tahun->update([
            'tanggal_mulai_daftar' => $request->tanggal_mulai_daftar,
            'tanggal_selesai_daftar' => $request->tanggal_selesai_daftar,
        ]);

        return redirect()->back()->with('success', 'Periode pendaftaran berhasil diatur!');
    }

    public function destroy($id)
    {
        $tahun = TahunAkademik::findOrFail($id);
        if ($tahun->is_active) {
            return redirect()->back()->with('error', 'Tahun akademik aktif tidak bisa dihapus!');
        }
        $tahun->delete();
        return redirect()->back()->with('success', 'Tahun Akademik berhasil dihapus!');
    }
}
