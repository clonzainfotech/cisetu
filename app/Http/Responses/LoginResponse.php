<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): Response
    {
        $user = $request->user();
        $host = $request->getHost();
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
        $redirectPath = Fortify::redirects('login');

        // 1. Check if we need a cross-domain redirect for Village Admins
        if ($user && $user->isVillageAdmin() && $user->village) {
            $subdomain = $user->village->subdomain;
            $targetHost = $subdomain.'.'.$baseDomain;

            if ($host !== $targetHost) {
                $protocol = $request->isSecure() ? 'https://' : 'http://';
                $redirectUrl = $protocol.$targetHost.$redirectPath;

                // Force session save before external redirect
                $request->session()->save();

                if ($request->header('X-Inertia') !== null) {
                    return Inertia::location($redirectUrl);
                }

                return redirect()->away($redirectUrl);
            }
        }

        // 2. Default same-domain redirect (Inertia will handle this as a SPA visit)
        if ($request->header('X-Inertia') !== null) {
            return redirect()->to($redirectPath);
        }

        if ($request->wantsJson()) {
            return new JsonResponse([
                'two_factor' => $request->session()->has('login.id'),
            ], 200);
        }

        return redirect()->to($redirectPath);
    }
}
