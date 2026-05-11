<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): Response
    {
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
        $protocol = $request->isSecure() ? 'https://' : 'http://';
        $redirectUrl = $protocol.$baseDomain.'/login';

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out']);
        }

        if ($request->header('X-Inertia') !== null) {
            return Inertia::location($redirectUrl);
        }

        // Redirect to main domain login to ensure a clean session state
        return redirect()->away($redirectUrl);
    }
}
