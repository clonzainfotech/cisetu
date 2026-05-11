<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LockoutResponse as LockoutResponseContract;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\LoginRateLimiter;
use Symfony\Component\HttpFoundation\Response;

class LockoutResponse implements LockoutResponseContract
{
    public function __construct(private LoginRateLimiter $limiter) {}

    public function toResponse($request): Response
    {
        $seconds = $this->limiter->availableIn($request);

        $message = trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);

        if ($request->wantsJson()) {
            return new JsonResponse([
                'message' => $message,
                'errors' => [
                    Fortify::username() => [$message],
                ],
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        // For normal web/Inertia visits, redirect back with themed toast + field error.
        return back()
            ->withErrors([Fortify::username() => $message])
            ->with('toast', [
                'type' => 'error',
                'message' => $message,
            ]);
    }
}
