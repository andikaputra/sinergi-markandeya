<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DosenMonev;
use App\Services\MonevMonitoring;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MonevMonitoringController extends Controller
{
    public function index(Request $request, MonevMonitoring $monitoring)
    {
        $request->validate(['kegiatan' => 'nullable|in:kkn,ppl,pkl,magang', 'q' => 'nullable|string|max:100', 'status' => 'nullable|in:semua,belum_mulai,tugas_belum,berjalan,lengkap,belum_login']);
        $allowed = array_map('strtolower', auth('web')->user()->getAllowedKegiatan());
        if ($request->filled('kegiatan')) {
            abort_unless(in_array($request->kegiatan, $allowed, true), 403);
        }
        $rows = $monitoring->assignments(DosenMonev::query())->filter(function ($row) use ($request, $allowed) {
            if (! in_array($row['activity'], $allowed, true) && ! (auth('web')->user()->isSuperAdmin() && $row['activity'] === '')) {
                return false;
            }
            if ($request->filled('kegiatan') && $row['activity'] !== $request->kegiatan) {
                return false;
            }
            if ($request->filled('q')) {
                $haystack = mb_strtolower(($row['dosen']?->nama ?? '').' '.$row['nidn'].' '.($row['dosen']?->nip ?? '').' '.$row['target']);
                if (! str_contains($haystack, mb_strtolower($request->q))) {
                    return false;
                }
            }

            return true;
        })->values();
        $lecturers = $monitoring->lecturers($rows);
        $summary = ['total' => $lecturers->count(), 'belum_mulai' => $lecturers->where('status', 'belum_mulai')->count(),
            'berjalan' => $lecturers->where('status', 'berjalan')->count(), 'lengkap' => $lecturers->where('status', 'lengkap')->count()];
        $status = $request->input('status') ?: 'belum_mulai';
        $filtered = $lecturers->filter(fn ($row) => $status === 'semua' || ($status === 'tugas_belum' ? $row['belum'] > 0 : ($status === 'belum_login' ? ($row['dosen'] && $row['dosen']->last_login === null) : $row['status'] === $status)))->values();
        if ($request->boolean('cetak')) {
            return view('reports.monev-monitoring', ['lecturers' => $filtered, 'status' => $status]);
        }
        $page = LengthAwarePaginator::resolveCurrentPage();
        $lecturers = new LengthAwarePaginator($filtered->forPage($page, 20)->values(), $filtered->count(), 20, $page, ['path' => $request->url(), 'query' => $request->query()]);

        return view('admin.dosen-monev.monitoring', compact('lecturers', 'summary', 'status', 'allowed'));
    }
}
