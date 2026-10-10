<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach (array_keys(\App\Services\ActivityAudit::MODELS) as $name) {
            $class = 'App\\Models\\'.$name;
            $class::observe(\App\Observers\ActivityAuditObserver::class);
        }
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            app(\App\Services\LoginActivityRecorder::class)->record($event->user, true);
        });
            if (config('app.env') === 'production') {
                \URL::forceScheme('https');
            }
    }
}
