<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InjectUserPermissions
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            Inertia::share('auth.permissions', function () use ($request) {
                return $request->user()->getPermissionNames();
            });
        }

        return $next($request);
    }
}
