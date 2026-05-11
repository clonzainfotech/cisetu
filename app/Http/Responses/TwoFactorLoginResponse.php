<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request): Response
    {
        // Inertia should be redirected to the two-factor challenge screen.
        if ($request->header('X-Inertia') !== null) {
            return redirect()->route('two-factor.login');
        }

        if ($request->wantsJson()) {
            return new JsonResponse([], 204);
        }

        return redirect()->intended(Fortify::redirects('login'));
    }
}
