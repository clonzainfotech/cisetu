<?php

namespace App\Http\Middleware;

use App\Models\Village;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IdentifyVillage
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
        $user = $request->user();

        // 1. Identify Village on Subdomain
        if ($host !== $baseDomain && str_ends_with($host, $baseDomain)) {
            $subdomain = str_replace('.'.$baseDomain, '', $host);

            if ($subdomain !== 'www') {
                $village = Village::where('subdomain', $subdomain)->where('is_active', true)->first();

                if ($village) {
                    $request->merge(['village' => $village]);
                    app()->instance(Village::class, $village);

                    // Enforce village isolation for authenticated users
                    if ($user && ! $user->isSuperMasterAdmin() && $user->village_id != $village->id && ! $request->routeIs('logout')) {
                        
                        // If they are visiting the LOGIN page, force a logout to allow a fresh login
                        if ($request->routeIs('login')) {
                            Auth::guard('web')->logout();
                            $request->session()->invalidate();
                            $request->session()->regenerateToken();
                            return $next($request);
                        }

                        // If they belong to a different village, redirect them there
                        if ($user->village_id && $user->village) {
                            $targetSubdomain = $user->village->subdomain;
                            $protocol = $request->isSecure() ? 'https://' : 'http://';
                            $redirectUrl = $protocol.$targetSubdomain.'.'.$baseDomain.'/dashboard';

                            $request->session()->save();
                            return redirect()->away($redirectUrl);
                        }

                        // If they have no valid village assignment, force logout
                        Auth::guard('web')->logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login')->withErrors([
                            'email' => 'You do not have access to the '.$village->name_en.' village portal.',
                        ]);
                    }
                }
            }
        }

        // 2. Main Domain Logic - Redirect Village Admins to their home subdomain
        if ($host === $baseDomain && $user && ! $user->isSuperMasterAdmin() && ! $request->routeIs('logout')) {
            if ($user->isVillageAdmin()) {
                $village = $user->village;
                if ($village && $village->subdomain) {
                    $protocol = $request->isSecure() ? 'https://' : 'http://';
                    $redirectUrl = $protocol.$village->subdomain.'.'.$baseDomain.'/dashboard';
                    
                    $request->session()->save();
                    return redirect()->away($redirectUrl);
                }
            }
        }

        return $next($request);
    }
}
