<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user,
            ],
            // Avoid loading all villages into every Inertia response for super admins.
            // The UI uses the `api/villages/search` endpoint for typeahead search.
            'villages' => [],
            'village' => $request->get('village')?->load('plan'),
            'subscription' => [
                'status' => $request->get('village')?->subscription_status ?? 'active',
                'is_suspended' => $request->get('village')?->subscription_status === 'suspended',
                'in_grace' => $request->get('village')?->isSubscriptionInGrace() ?? false,
                'due_soon' => $request->get('village') &&
                             $request->get('village')->subscription_status === 'active' &&
                             $request->get('village')->subscription_expires_at &&
                             $request->get('village')->subscription_expires_at->isFuture() &&
                             now()->diffInDays($request->get('village')->subscription_expires_at, false) <= 15,
                'expired' => $request->get('village')?->isSubscriptionExpired() ?? false,
                'days_left' => $request->get('village') && ! $request->get('village')->isSubscriptionExpired() && $request->get('village')->subscription_expires_at
                             ? (int) now()->diffInDays($request->get('village')->subscription_expires_at, false)
                             : ($request->get('village')?->isSubscriptionExpired() ? 0 : null),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'toast' => $request->session()->get('toast') ?? Inertia::getFlashed($request)['toast'] ?? null,
            'inquiry_prefill' => $request->session()->get('inquiry_prefill'),
            'csrf_token' => csrf_token(),
            'app_url' => config('app.url'),
        ];
    }
}
