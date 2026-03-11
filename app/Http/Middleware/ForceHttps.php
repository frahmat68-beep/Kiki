<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        $forwardedProto = strtolower(trim((string) $request->header('x-forwarded-proto', '')));
        $isForwardedHttps = $forwardedProto !== '' && str_contains($forwardedProto, 'https');

        if (app()->environment('production') && ! $request->secure() && ! $isForwardedHttps) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
