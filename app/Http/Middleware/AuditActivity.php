<?php

namespace App\Http\Middleware;

use App\Services\ActivityAudit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuditActivity
{
    public function handle(Request $request, Closure $next)
    {
        $request->attributes->set('audit_enabled', true);
        $request->attributes->set('audit_request_id', (string) Str::uuid());
        $request->attributes->set('audit_entries', 0);
        $request->attributes->set('audit_model_entries', 0);
        $audit = app(ActivityAudit::class);
        $beforeActor = $audit->actor();
        // Domain writes and their audit rows commit or roll back together. Auth
        // endpoints also call external AIS and are recorded through login events.
        $transaction = ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)
            && ! preg_match('#^(login|loginadmin|logout|logoutadmin|sso/|api/|lupa-password|reset-password)#', $request->path());
        if ($transaction) {
            DB::beginTransaction();
        }
        try {
            $response = $next($request);
            $newFlash = $request->hasSession() ? $request->session()->get('_flash.new', []) : [];
            $redirectPath = parse_url($response->headers->get('Location', ''), PHP_URL_PATH);
            $loginPaths = [parse_url(route('login'), PHP_URL_PATH), parse_url(route('loginadmin'), PHP_URL_PATH)];
            $authFlow = preg_match('#^(login|loginadmin|logout|logoutadmin|register|sso/|api/|lupa-password|reset-password)#', $request->path());
            $authRedirect = ! $authFlow && $response->isRedirect() && in_array($redirectPath, $loginPaths, true)
                && ! in_array('success', $newFlash, true) && $request->attributes->get('audit_model_entries') === 0;
            $failed = $response->getStatusCode() >= 400 || array_intersect($newFlash, ['errors', 'error']) || $authRedirect;
            if ($failed) {
                if ($transaction) {
                    DB::rollBack();
                }

                return $response;
            }
            $name = $request->route()?->getName() ?? '';
            $actor = $audit->actor();
            $logoutActor = $request->attributes->has('audit_actor') || $beforeActor['actor_type'] === 'tamu' ? $actor : $beforeActor;
            if (in_array($request->route()?->uri(), ['logout', 'logoutadmin', 'api/v1/logout', 'api/sso/logout'], true) && $logoutActor['actor_type'] !== 'tamu') {
                $audit->write('authentication', 'logout', [], $logoutActor);
            } elseif ($actor['actor_type'] !== 'tamu') {
                $module = $audit->routeModule();
                $action = null;
                if ($request->boolean('cetak') || str_contains($name, '.print.') || str_ends_with($name, '.cetak')) {
                    $action = 'report_opened';
                } elseif (str_contains($name, 'export')) {
                    $action = 'export_prepared';
                } elseif (in_array($name, ['bimbingan.berkas', 'luaran.berkas'], true)) {
                    $action = 'download_prepared';
                } elseif (in_array($request->method(), ['GET', 'HEAD'], true) && $module && ! str_starts_with($name, 'admin.activity')) {
                    $action = 'viewed';
                } elseif ($transaction && $module && $request->attributes->get('audit_entries') === 0) {
                    $action = 'processed';
                }
                if ($action) {
                    $audit->write($module ?? 'laporan', $action, $audit->requestContext() + [
                        'subject_label' => Str::limit($name ?: $request->route()?->uri() ?: 'Permintaan', 200),
                    ]);
                }
            }
            if ($transaction) {
                DB::commit();
            }

            return $response;
        } catch (\Throwable $error) {
            if ($transaction && DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            throw $error;
        } finally {
            $request->attributes->set('audit_enabled',false);
        }
    }
}
