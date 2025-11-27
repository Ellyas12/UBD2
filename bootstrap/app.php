<?php

use App\Console\Commands\DeleteOldProgramBackups;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->append([
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // Web group (just bindings + CSRF)
        $middleware->group('web', [
            \Illuminate\Session\Middleware\StartSession::class,   // starts session
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // shares errors with views
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\PreventBackHistory::class,
        ]);

        // API group (simple bindings)
        $middleware->group('api', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Middleware aliases
        $middleware->alias([
            'inactivity' => \App\Http\Middleware\Inactivity::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'posisi' => \App\Http\Middleware\CheckPosisi::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
            'ensure.email.submitted' => \App\Http\Middleware\EnsureEmailSubmitted::class,
            'ensure.code.verified' => \App\Http\Middleware\EnsureCodeVerified::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
    })

    ->withCommands([
        DeleteOldProgramBackups::class,
    ])
    
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('backups:clean')->daily();
    })

    ->create();