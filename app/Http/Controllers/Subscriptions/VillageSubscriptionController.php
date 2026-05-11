<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionHistory;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Village;
use App\Support\UrlSafeId;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class VillageSubscriptionController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin(), 403);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'limit' => ['nullable', 'integer', Rule::in([10, 25, 50, 100])],
        ]);

        $search = $validated['search'] ?? null;
        $limit = (int) ($validated['limit'] ?? 10);

        $villages = Village::query()
            ->with(['plan'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name_en', 'like', "%{$search}%")
                        ->orWhere('subdomain', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('LOWER(name_en)')
            ->paginate($limit)
            ->withQueryString()
            ->through(function (Village $village) {
                $statusLabel = 'none';

                if (! $village->is_active) {
                    $statusLabel = 'inactive';
                } elseif ($village->subscription_status === 'suspended') {
                    $statusLabel = 'suspended';
                } elseif ($village->isSubscriptionExpired()) {
                    $statusLabel = 'expired';
                } elseif ($village->isSubscriptionInGrace()) {
                    $statusLabel = 'grace';
                } elseif ($village->isSubscriptionActive()) {
                    $statusLabel = 'active';
                }

                return [
                    'id' => $village->id,
                    'token' => UrlSafeId::encrypt($village->id),
                    'name_en' => $village->name_en,
                    'subdomain' => $village->subdomain,
                    'subscription' => $village->subscription_plan_id ? [
                        'plan' => [
                            'id' => $village->plan->id,
                            'name' => $village->plan->name,
                            'code' => $village->plan->code,
                        ],
                        'starts_at' => $village->subscription_start_at?->toDateString(),
                        'ends_at' => $village->subscription_expires_at?->toDateString(),
                        'grace_ends_at' => $village->subscription_grace_ends_at?->toDateString(),
                    ] : null,
                    'status' => $statusLabel,
                ];
            });

        return Inertia::render('subscriptions/Index', [
            'villages' => $villages,
            'filters' => [
                'search' => $search,
                'limit' => $limit,
            ],
        ]);
    }

    public function edit(Request $request, string $villageToken): Response
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin(), 403);

        try {
            $villageId = UrlSafeId::decryptToInt($villageToken);
        } catch (\InvalidArgumentException) {
            abort(404);
        }

        $village = Village::query()->findOrFail($villageId);
        $village->load(['plan']);

        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByRaw('LOWER(name)')
            ->get(['id', 'code', 'name', 'max_user_accounts']);

        $history = SubscriptionHistory::query()
            ->where('village_id', $village->id)
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn (SubscriptionHistory $h) => [
                'id' => $h->id,
                'event_type' => $h->event_type,
                'previous_ends_at' => $h->previous_ends_at?->toDateString(),
                'new_ends_at' => $h->new_ends_at?->toDateString(),
                'note' => $h->note,
                'created_at' => $h->created_at?->toDateTimeString(),
            ]);

        return Inertia::render('subscriptions/Edit', [
            'village' => [
                'id' => $village->id,
                'token' => UrlSafeId::encrypt($village->id),
                'name_en' => $village->name_en,
                'subdomain' => $village->subdomain,
            ],
            'subscription' => $village->subscription_plan_id ? [
                'plan_id' => $village->subscription_plan_id,
                'status' => $village->subscription_status,
                'starts_at' => $village->subscription_start_at?->toDateString(),
                'ends_at' => $village->subscription_expires_at?->toDateString(),
                'grace_ends_at' => $village->subscription_grace_ends_at?->toDateString(),
                'billing_reference' => $village->subscription_billing_reference,
            ] : null,
            'plans' => $plans,
            'history' => $history,
        ]);
    }

    public function update(Request $request, string $villageToken): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin(), 403);

        try {
            $villageId = UrlSafeId::decryptToInt($villageToken);
        } catch (\InvalidArgumentException) {
            abort(404);
        }

        $village = Village::query()->findOrFail($villageId);

        $validated = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:subscription_plans,id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'grace_ends_at' => ['nullable', 'date', 'after_or_equal:ends_at'],
            'status' => ['required', 'string', 'in:active,grace,expired,suspended'],
            'note' => ['nullable', 'string', 'max:512'],
        ]);

        $previousEndsAt = $village->subscription_expires_at;

        $village->fill([
            'subscription_plan_id' => (int) $validated['plan_id'],
            'subscription_status' => $validated['status'],
            'subscription_start_at' => $validated['starts_at'],
            'subscription_expires_at' => $validated['ends_at'],
            'subscription_grace_ends_at' => $validated['grace_ends_at'],
        ])->save();

        SubscriptionHistory::query()->create([
            'village_id' => $village->id,
            'plan_id' => $village->subscription_plan_id,
            'event_type' => $previousEndsAt ? 'note' : 'created',
            'previous_ends_at' => $previousEndsAt,
            'new_ends_at' => $village->subscription_expires_at,
            'performed_by_user_id' => $actor->id,
            'note' => $validated['note'] ?? null,
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Subscription updated.')]);

        return to_route('subscriptions.edit', UrlSafeId::encrypt($village->id));
    }

    public function plans(Request $request): Response
    {
        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('subscriptions/Plans', [
            'plans' => $plans,
        ]);
    }
}
