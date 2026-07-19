<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $nim = Auth::guard('mahasiswa')->user()->nim;
        $notifikasis = Notifikasi::where('nim', $nim)->orderByDesc('created_at')->paginate(20);

        // Tandai semua sebagai dibaca
        Notifikasi::where('nim', $nim)->where('is_read', false)->update(['is_read' => true]);

        return view('mahasiswa.notifikasi', compact('notifikasis'));
    }

    public function markRead($id)
    {
        $nim = Auth::guard('mahasiswa')->user()->nim;
        Notifikasi::where('id', $id)->where('nim', $nim)->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    }
}
