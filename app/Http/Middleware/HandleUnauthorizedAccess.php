<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Inertia\Inertia;

class HandleUnauthorizedAccess
{
    public function handle(Request $request, Closure $next, $permission = null, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (! is_null($permission)) {
            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);

            foreach ($permissions as $permission) {
                if (Auth::guard($guard)->user()->can($permission)) {
                    return $next($request);
                }
            }

            return Inertia::render('errors/Unauthorized')->toResponse($request)->setStatusCode(403);
        }

        return $next($request);
    }
}