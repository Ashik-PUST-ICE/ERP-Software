<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class VersionUpdate
{

    public function handle(Request $request, Closure $next)
    {
        // The local updater must remain reachable while an update is pending.
        if ($request->is('erp/super-admin/version-update*')) {
            return $next($request);
        }

        $codeBuildVersion = config('app.build_version');
        $dbBuildVersion = getCustomerCurrentBuildVersion();

        if ($codeBuildVersion > $dbBuildVersion) {
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Auth::logout();
            if (!file_exists(storage_path('installed')) && !file_exists(storage_path('ashik-installed'))) {
                return redirect()->route('ashik.install');
            }
            return redirect()->route('ashik.version-update');

        }
        return $next($request);
    }
}
