<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $currentRole = $user->role ?? 'user';

        if (! in_array($currentRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
