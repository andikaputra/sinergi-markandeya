<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function authorizeManagedStudents(array $nims): void
    {
        $admin = \Illuminate\Support\Facades\Auth::guard('web')->user();
        abort_unless($admin, 403);
        if ($admin->isSuperAdmin()) {
            return;
        }

        foreach (\App\Models\Mahasiswa::whereIn('nim', $nims)->get() as $student) {
            $activity = $student->kegiatan;
            abort_unless(is_string($activity) && $admin->canManage($activity), 403,
                'Anda tidak memiliki akses untuk kegiatan mahasiswa ini.');
        }
    }
}
