<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InstallMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('ashik-install*')) {
            return $next($request);
        }

        if (file_exists(storage_path('installed')) || file_exists(storage_path('ashik-installed'))) {
            return $next($request);
        }

        return redirect()->route('ashik.install');
    }
}
