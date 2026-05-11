<?php

use App\Http\Middleware\ForceHttps;
use Illuminate\Http\Request;

test('does not redirect http requests outside production environments', function () {
    expect(app()->environment())->not->toBe('production');

    config(['app.url' => 'https://cis.test']);

    $middleware = app(ForceHttps::class);

    $request = Request::create('http://cis.test/login', 'GET');
    $response = $middleware->handle($request, fn () => response('ok'));

    expect($response->getStatusCode())->toBe(200);
    expect($response->getContent())->toBe('ok');
});

test('redirects insecure requests when environment is production and app url scheme is https', function () {
    $previous = app()->environment();

    app()->detectEnvironment(static fn (): string => 'production');

    try {
        config(['app.url' => 'https://cis.test']);

        $middleware = app(ForceHttps::class);

        $request = Request::create('http://cis.test/login', 'GET');
        $response = $middleware->handle($request, fn (): never => abort(500));

        expect($response->isRedirect())->toBeTrue();
        expect($response->headers->get('Location'))->toBe('https://cis.test/login');
    } finally {
        app()->detectEnvironment(static fn (): string => $previous);
    }
});
