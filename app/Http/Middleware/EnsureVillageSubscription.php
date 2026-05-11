<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Village;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVillageSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Village|null $village */
        $village = $request->get('village');

        // If no village is identified (e.g., master admin domain), skip check
        if (! $village || ! ($village instanceof Village)) {
            return $next($request);
        }

        /** @var User|null $user */
        $user = $request->user();

        // Super Master Admins can always access everything
        if ($user && $user->isSuperMasterAdmin()) {
            return $next($request);
        }

        // Routes that MUST remain accessible even if subscription is expired
        $allowedRoutes = [
            'contact-us',
            'contact-us.store',
            'logout',
            'login',
            'home',
        ];

        if ($request->routeIs($allowedRoutes)) {
            return $next($request);
        }

        // Check for deactivation
        if (! $village->is_active) {
            return redirect()->route('contact-us')->with([
                'error' => 'The portal for '.$village->name_en.' is currently inactive. Please contact us for assistance.',
            ]);
        }

        // Check for suspension
        if ($village->subscription_status === 'suspended') {
            return redirect()->route('contact-us')->with([
                'error' => 'Your subscription for '.$village->name_en.' has been suspended. Please contact us for more information.',
            ]);
        }

        // Check for expiration
        if ($village->isSubscriptionExpired()) {
            return redirect()->route('contact-us')->with([
                'error' => 'Your subscription for '.$village->name_en.' has expired. Please contact us to renew.',
            ]);
        }

        return $next($request);
    }
}
