<?php

namespace App\Http\Responses;

use App\Models\Village;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements LogoutResponseContract
{
    /** @var list<string> */
    private const RESERVED_SUBDOMAINS = [
        'www',
        'admin',
        'api',
        'app',
        'mail',
        'staging',
    ];

    public function toResponse($request): Response
    {
        $redirectUrl = $this->resolveRedirectUrl($request);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out']);
        }

        if ($request->header('X-Inertia') !== null) {
            return Inertia::location($redirectUrl);
        }

        return redirect()->away($redirectUrl);
    }

    private function resolveRedirectUrl(Request $request): string
    {
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
        $protocol = $request->isSecure() ? 'https://' : 'http://';
        $host = $request->getHost();

        if ($host !== $baseDomain && str_ends_with($host, '.'.$baseDomain)) {
            $subdomain = str_replace('.'.$baseDomain, '', $host);

            if ($subdomain !== 'www' && ! in_array($subdomain, self::RESERVED_SUBDOMAINS, true)) {
                $villageExists = Village::query()
                    ->where('subdomain', $subdomain)
                    ->exists();

                if ($villageExists) {
                    return $protocol.$subdomain.'.'.$baseDomain.'/';
                }
            }
        }

        return $protocol.$baseDomain.'/login';
    }
}
