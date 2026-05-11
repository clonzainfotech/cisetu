<?php

use App\Http\Middleware\EnsureVillageSubscription;
use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\IdentifyVillage;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);
        $middleware->validateCsrfTokens(except: []);

        //
        // Proxies reverse-proxy headers (common on local dev setups). This prevents Laravel
        // from incorrectly detecting HTTPS and issuing Secure-only cookies while browsing on HTTP,
        // which manifests as intermittent 419s on POST (CSRF/session cookies not sent).
        //
        //
        // IMPORTANT: don't call the application container here (bootstrap is still wiring services).
        //
        if (($env = $_ENV['APP_ENV'] ?? getenv('APP_ENV')) === 'local') {
            $middleware->trustProxies(at: '*');
        }

        $middleware->redirectUsersTo('/dashboard');

        $middleware->web(append: [
            IdentifyVillage::class,
            EnsureVillageSubscription::class,
            ForceHttps::class,
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
