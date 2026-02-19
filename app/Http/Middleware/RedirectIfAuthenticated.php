<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                if ($guard === 'admin') {
                    return redirect('/admin/dashboard');
                }

                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
