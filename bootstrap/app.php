<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/login',
            users: '/home'
        );
        $middleware->trustProxies(at: '*');

        // Bypass CSRF for cart routes, API endpoints, tests, and testing environment
        $except = ['cart/*', 'api/*', 'payment/vnpay/callback', 'payment/vnpay/return'];
        if (env('APP_ENV') === 'testing') {
            $except[] = '*'; // Disable CSRF for all routes in testing
        }
        $middleware->validateCsrfTokens(except: $except);


        $middleware->web(append: [
            \App\Http\Middleware\SetLanguage::class,
            \App\Http\Middleware\PreventBackHistory::class,
            \App\Http\Middleware\TrackRecentlyViewed::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.only' => \App\Http\Middleware\AdminOnlyMiddleware::class,
            'admin.lock' => \App\Http\Middleware\AdminLockMiddleware::class,
            'staff' => \App\Http\Middleware\StaffMiddleware::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()->back()->withInput()->with('error', __('messages.session_expired') ?? 'Phiên làm việc đã hết hạn. Vui lòng thử lại.');
        });
    })->create();
