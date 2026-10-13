<?php

namespace App\Observers;

use App\Services\ActivityAudit;
use Illuminate\Database\Eloquent\Model;

class ActivityAuditObserver
{
    public function created(Model $model): void
    {
        app(ActivityAudit::class)->modelChange($model, 'created');
    }

    public function updated(Model $model): void
    {
        app(ActivityAudit::class)->modelChange($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        app(ActivityAudit::class)->modelChange($model, 'deleted');
    }
}
