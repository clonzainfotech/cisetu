<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function resolvePerPageLimit(Request $request, int $default = 10): int
    {
        $limit = (int) $request->input('limit', $default);

        return in_array($limit, [10, 25, 50, 100], true) ? $limit : $default;
    }

    protected function baseDomainHost(): string
    {
        return parse_url(config('app.url'), PHP_URL_HOST);
    }

    protected function isHostForSubdomain(Request $request, string $subdomain): bool
    {
        return $request->getHost() === $subdomain.'.'.$this->baseDomainHost();
    }

    protected function mainDomainUrl(Request $request, string $path = '/'): string
    {
        $protocol = $request->isSecure() ? 'https' : 'http';

        return $protocol.'://'.$this->baseDomainHost().$path;
    }
}
