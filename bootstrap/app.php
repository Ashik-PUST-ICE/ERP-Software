<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/frontend.php'));

            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/super_admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/user.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'super-admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
            'version.update' => \App\Http\Middleware\VersionUpdate::class,
            'installed' => \App\Http\Middleware\InstallMiddleware::class,
            'isDemo' => \App\Http\Middleware\IsDemo::class,
            '2fa_verify' => \App\Http\Middleware\Google2FAAuthentication::class,
            'is_email_verify' => \App\Http\Middleware\IsVerifyMiddleware::class,
            'common' => \App\Http\Middleware\CommonMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
