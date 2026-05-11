<?php

namespace App\Http\Controllers\Villages;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class VillageController extends Controller
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
            ->with(['district.state'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('villages.name_en', 'like', "%{$search}%")
                        ->orWhere('villages.subdomain', 'like', "%{$search}%")
                        ->orWhereHas('district', function ($d) use ($search) {
                            $d->where('districts.name_en', 'like', "%{$search}%")
                                ->orWhereHas('state', fn ($s) => $s->where('states.name_en', 'like', "%{$search}%"));
                        });
                });
            })
            ->orderByRaw('LOWER(villages.name_en)')
            ->paginate($limit)
            ->withQueryString()
            ->through(fn (Village $village) => [
                'id' => $village->id,
                'name_en' => $village->name_en,
                'name_local' => $village->name_local,
                'subdomain' => $village->subdomain,
                'logo_url' => $village->logo ? asset('storage/'.$village->logo) : null,
                'upi_id' => $village->upi_id,
                'upi_name' => $village->upi_name,
                'payment_note' => $village->payment_note,
                'whatsapp_number' => $village->whatsapp_number,
                'subscription_status' => $village->subscription_status,
                'is_active' => $village->is_active,
                'admin_email' => $village->users()->where('role', 'village_admin')->first()?->email,
                'portal_template' => $village->portal_template,
                'subscription_plan_id' => $village->subscription_plan_id,
                'subscription_start_at' => $village->subscription_start_at?->toDateString(),
                'subscription_expires_at' => $village->subscription_expires_at?->toDateString(),
                'district' => [
                    'id' => $village->district->id,
                    'name_en' => $village->district->name_en,
                    'state' => [
                        'name_en' => $village->district->state->name_en,
                    ],
                ],
            ]);

        $districts = District::query()
            ->with('state')
            ->orderByRaw('LOWER(name_en)')
            ->get()
            ->map(fn (District $d) => [
                'id' => $d->id,
                'name_en' => $d->name_en,
                'state_name_en' => $d->state->name_en,
            ]);

        $plans = \App\Models\SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByRaw('LOWER(name)')
            ->get(['id', 'code', 'name']);

        return Inertia::render('villages/Index', [
            'villages' => $villages,
            'filters' => [
                'search' => $search,
                'limit' => $limit,
            ],
            'districts' => $districts,
            'plans' => $plans,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin(), 403);

        $validated = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'name_en' => ['required', 'string', 'max:100'],
            'name_local' => ['nullable', 'string', 'max:100'],
            'subdomain' => ['required', 'string', 'max:50', 'alpha_num', 'unique:villages,subdomain'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'upi_id' => ['nullable', 'string', 'max:100'],
            'upi_name' => ['nullable', 'string', 'max:100'],
            'payment_note' => ['nullable', 'string', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'regex:/^(\+91|91|0)?[6-9]\d{9}$/'],
            'portal_template' => ['required', 'string', Rule::in(['default', 'premium', 'modern'])],
            'is_active' => ['required', 'boolean'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
            'password_length' => ['required', 'integer', 'min:6', 'max:32'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'subscription_start_at' => ['required', 'date'],
            'subscription_expires_at' => ['required', 'date', 'after_or_equal:subscription_start_at'],
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $village = new Village;
            $village->district_id = $validated['district_id'];
            $village->name_en = $validated['name_en'];
            $village->name_local = $validated['name_local'];
            $village->subdomain = $validated['subdomain'];
            $village->upi_id = $validated['upi_id'];
            $village->upi_name = $validated['upi_name'];
            $village->payment_note = $validated['payment_note'];
            $village->whatsapp_number = $validated['whatsapp_number'];
            $village->portal_template = $validated['portal_template'];
            $village->is_active = $validated['is_active'];
            $village->password_length = $validated['password_length'];
            $village->subscription_plan_id = $validated['subscription_plan_id'];
            $village->subscription_start_at = $validated['subscription_start_at'];
            $village->subscription_expires_at = $validated['subscription_expires_at'];
            $village->subscription_status = 'active';

            if ($request->hasFile('logo')) {
                $village->logo = $request->file('logo')->store('logos', 'public');
            }

            $village->save();

            // Create Admin User
            $user = new User;
            $user->village_id = $village->id;
            $user->name = $validated['admin_name'];
            $user->email = $validated['admin_email'];
            $user->password = Hash::make($validated['admin_password']);
            $user->role = 'village_admin';
            $user->is_active = true;
            $user->email_verified_at = now();
            $user->save();

            return redirect()->route('villages.index')->with('toast', [
                'type' => 'success',
                'message' => 'Village created successfully',
            ]);
        });
    }

    public function update(Request $request, Village $village): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin(), 403);

        $validated = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'name_en' => ['required', 'string', 'max:100'],
            'name_local' => ['nullable', 'string', 'max:100'],
            'subdomain' => ['required', 'string', 'max:50', 'alpha_num', Rule::unique('villages')->ignore($village->id)],
            'logo' => ['nullable', 'image', 'max:2048'],
            'upi_id' => ['nullable', 'string', 'max:100'],
            'upi_name' => ['nullable', 'string', 'max:100'],
            'payment_note' => ['nullable', 'string', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'regex:/^(\+91|91|0)?[6-9]\d{9}$/'],
            'portal_template' => ['required', 'string', Rule::in(['default', 'premium', 'modern'])],
            'is_active' => ['required', 'boolean'],
            'password_length' => ['required', 'integer', 'min:6', 'max:32'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'subscription_start_at' => ['required', 'date'],
            'subscription_expires_at' => ['required', 'date', 'after_or_equal:subscription_start_at'],
        ]);

        $village->district_id = $validated['district_id'];
        $village->name_en = $validated['name_en'];
        $village->name_local = $validated['name_local'];
        $village->subdomain = $validated['subdomain'];
        $village->upi_id = $validated['upi_id'];
        $village->upi_name = $validated['upi_name'];
        $village->payment_note = $validated['payment_note'];
        $village->whatsapp_number = $validated['whatsapp_number'];
        $village->portal_template = $validated['portal_template'];
        $village->is_active = $validated['is_active'];
        $village->password_length = $validated['password_length'];
        $village->subscription_plan_id = $validated['subscription_plan_id'];
        $village->subscription_start_at = $validated['subscription_start_at'];
        $village->subscription_expires_at = $validated['subscription_expires_at'];

        if ($request->hasFile('logo')) {
            if ($village->logo) {
                Storage::disk('public')->delete($village->logo);
            }
            $village->logo = $request->file('logo')->store('logos', 'public');
        }

        $village->save();

        return redirect()->route('villages.index')->with('toast', [
            'type' => 'success',
            'message' => 'Village updated successfully',
        ]);
    }

    public function destroy(Village $village): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin(), 403);

        if ($village->logo) {
            Storage::disk('public')->delete($village->logo);
        }

        $village->delete();

        return redirect()->route('villages.index')->with('toast', [
            'type' => 'success',
            'message' => 'Village deleted successfully',
        ]);
    }
}
