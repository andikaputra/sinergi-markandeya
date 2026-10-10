<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'module' => ['nullable', Rule::in(array_keys(ActivityLog::MODULES))],
            'action' => ['nullable', Rule::in(array_keys(ActivityLog::ACTIONS))],
            'actor_type' => 'nullable|in:admin,dosen,mahasiswa,pembimbing_luar,tamu',
            'q' => 'nullable|string|max:100', 'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => ['nullable', 'date', ...($request->filled('tanggal_mulai') ? ['after_or_equal:tanggal_mulai'] : [])],
        ]);
        $query = ActivityLog::query();
        $admin = auth('web')->user();
        if (! $admin->isSuperAdmin()) {
            $query->where(function ($query) use ($admin) {
                $query->whereIn('activity', $admin->getAllowedKegiatan())->orWhere('module', 'authentication')
                    ->orWhere(fn ($own) => $own->where('actor_type', 'admin')->where('actor_id', $admin->id));
            });
        }
        foreach (['module', 'action', 'actor_type'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }
        if ($request->filled('q')) {
            $query->where(function ($query) use ($request) {
                foreach (['actor_name', 'actor_identifier', 'subject_label', 'subject_id'] as $index => $field) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $query->$method($field, 'like', '%'.$request->q.'%');
                }
            });
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('occurred_at', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('occurred_at', '<=', $request->tanggal_selesai);
        }
        $query->orderByDesc('occurred_at')->orderByDesc('id');
        if ($request->boolean('cetak')) {
            $logs = $query->get();

            return view('reports.activity-logs', compact('logs'));
        }
        $logs = $query->paginate(30)->withQueryString();

        return view('admin.activity-logs.index', compact('logs'));
    }
}
