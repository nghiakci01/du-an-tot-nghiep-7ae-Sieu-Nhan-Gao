<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminLockMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If session has 'locked' key and user is not accessing lock/unlock routes
        if (Session::has('locked') && Session::get('locked') === true) {

            // Allow access to unlock route to prevent redirect loop
            if ($request->routeIs('admin.unlock') || $request->routeIs('admin.unlock.submit') || $request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('admin.unlock');
        }

        // Prevent accessing lock screen if not locked
        if ($request->routeIs('admin.unlock') && (!Session::has('locked') || Session::get('locked') !== true)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
