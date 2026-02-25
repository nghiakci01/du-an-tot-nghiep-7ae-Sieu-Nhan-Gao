<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/login',
            users: '/home'
        );
        $middleware->trustProxies(at: '*');

        // Bypass CSRF for cart routes (debug - remove after fix)
        $middleware->validateCsrfTokens(except: [
            'cart/*',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLanguage::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.only' => \App\Http\Middleware\AdminOnlyMiddleware::class,
            'admin.lock' => \App\Http\Middleware\AdminLockMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
