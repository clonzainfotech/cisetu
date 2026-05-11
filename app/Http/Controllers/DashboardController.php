<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Inquiry;
use App\Models\Shop;
use App\Models\SubscriptionPlan;
use App\Models\Village;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $village = $request->get('village') ?: $request->user()->village;

        if (! $village) {
            $user = $request->user();
            if ($user->isSuperMasterAdmin()) {
                $totalHomeRevenue = Home::sum('total');
                $totalShopRevenue = Shop::sum('total');

                $totalHomeRecords = Home::count();
                $totalShopRecords = Shop::count();

                $inquiries = Inquiry::with('plan')->latest()->take(10)->get();

                return Inertia::render('Dashboard', [
                    'stats' => [
                        'is_super_admin' => true,
                        'total_villages' => Village::count(),
                        'active_villages' => Village::where('is_active', true)
                            ->where('subscription_status', '!=', 'suspended')
                            ->where('subscription_status', '!=', 'expired')
                            ->where(function ($query) {
                                $query->whereNull('subscription_expires_at')
                                    ->orWhere('subscription_expires_at', '>', now())
                                    ->orWhere(function ($q) {
                                        $q->whereNotNull('subscription_grace_ends_at')
                                            ->where('subscription_grace_ends_at', '>', now());
                                    });
                            })
                            ->count(),
                        'expired_villages' => Village::where('is_active', true)
                            ->where(function ($query) {
                                $query->where('subscription_status', 'expired')
                                    ->orWhere(function ($q) {
                                        $q->whereNotNull('subscription_expires_at')
                                            ->where('subscription_expires_at', '<=', now())
                                            ->where(function ($sub) {
                                                $sub->whereNull('subscription_grace_ends_at')
                                                    ->orWhere('subscription_grace_ends_at', '<=', now());
                                            });
                                    });
                            })
                            ->count(),
                        'total_revenue' => $totalHomeRevenue + $totalShopRevenue,
                        'total_records' => $totalHomeRecords + $totalShopRecords,
                        'home_records' => $totalHomeRecords,
                        'shop_records' => $totalShopRecords,
                        'home_revenue' => $totalHomeRevenue,
                        'shop_revenue' => $totalShopRevenue,
                        'pending_inquiries' => Inquiry::where('status', 'pending')->count(),
                        'plans_breakdown' => SubscriptionPlan::where('is_active', true)
                            ->orderBy('sort_order')
                            ->get(['id', 'name', 'code'])
                            ->map(fn ($plan) => [
                                'name' => $plan->name,
                                'code' => $plan->code,
                                'count' => Village::where('subscription_plan_id', $plan->id)->count(),
                            ]),
                    ],
                    'recent_inquiries' => $inquiries,
                ]);
            }

            return Inertia::render('Dashboard', [
                'stats' => null,
            ]);
        }

        $totalHomes = Home::where('village_id', $village->id)->count();
        $totalShops = Shop::where('village_id', $village->id)->count();
        $totalHomeTax = Home::where('village_id', $village->id)->sum('total');
        $totalShopTax = Shop::where('village_id', $village->id)->sum('total');

        return Inertia::render('Dashboard', [
            'stats' => [
                'is_super_admin' => false,
                'total_properties' => $totalHomes + $totalShops,
                'total_homes' => $totalHomes,
                'total_shops' => $totalShops,
                'total_revenue' => $totalHomeTax + $totalShopTax,
                'home_revenue' => $totalHomeTax,
                'shop_revenue' => $totalShopTax,
            ],
        ]);
    }
}
