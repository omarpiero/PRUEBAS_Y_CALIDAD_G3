<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()) {
            abort(401, 'No autenticado.');
        }

        // Admin has total access (bypass)
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        if (! $request->user()->hasRole($role)) {
            abort(403, 'No tienes el rol requerido para realizar esta acción.');
        }

        return $next($request);
    }
}
