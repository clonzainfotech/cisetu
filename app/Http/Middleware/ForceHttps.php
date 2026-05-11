<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        //
        // Only enforce HTTPS in production: local/staging/testing often reuse a production-ish
        // APP_URL=https://..., which would wrongly 301 localhost HTTP and break sessions/CSRF.
        //
        if (! app()->environment('production')) {
            return $next($request);
        }

        $appUrl = config('app.url');

        if (! $request->isSecure() && is_string($appUrl) && str_starts_with($appUrl, 'https://')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
