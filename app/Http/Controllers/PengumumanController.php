<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderByDesc('created_at')->paginate(15);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'isi'    => 'required|string',
            'target' => 'required|in:semua,KKN,PPL,PKL,Magang',
        ]);

        Pengumuman::create([
            'judul'    => $request->judul,
            'isi'      => $request->isi,
            'target'   => $request->target,
            'admin_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil disimpan!');
    }

    public function publish($id)
    {
        Pengumuman::findOrFail($id)->publish();

        // Kirim notifikasi ke mahasiswa yang relevan
        $p = Pengumuman::findOrFail($id);
        $mahasiswas = \App\Models\Mahasiswa::where('status', 'aktif')->get();
        foreach ($mahasiswas as $mhs) {
            $keg = $mhs->kegiatan;
            if ($p->target === 'semua' || $p->target === $keg) {
                \App\Models\Notifikasi::kirim(
                    $mhs->nim,
                    'Pengumuman: ' . $p->judul,
                    \Illuminate\Support\Str::limit($p->isi, 120),
                    'info'
                );
            }
        }

        return redirect()->back()->with('success', 'Pengumuman dipublikasikan!');
    }

    public function unpublish($id)
    {
        Pengumuman::findOrFail($id)->unpublish();
        return redirect()->back()->with('success', 'Pengumuman disembunyikan.');
    }

    public function destroy($id)
    {
        Pengumuman::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Pengumuman dihapus.');
    }
}
