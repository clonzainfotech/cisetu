<?php

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Cookie;

test('database sessions issue non-secure session cookies when session.secure=false', function () {
    config([
        'session.driver' => 'database',
        'session.secure' => false,
    ]);

    expect(DB::table('sessions')->count())->toBe(0);

    $response = $this->get(route('login'));

    $response->assertOk();

    $cookies = collect($response->headers->getCookies())
        ->keyBy(fn ($cookie) => $cookie->getName());

    /** @var Cookie $sessionCookie */
    $sessionCookie = $cookies->get(config('session.cookie'));

    expect($sessionCookie)->not->toBeNull();
    expect($sessionCookie->isSecure())->toBeFalse();

    expect(DB::table('sessions')->count())->toBe(1);
});
