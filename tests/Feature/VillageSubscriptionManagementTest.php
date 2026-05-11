<?php

use App\Models\District;
use App\Models\State;
use App\Models\SubscriptionHistory;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Village;
use App\Models\VillageSubscription;
use App\Support\UrlSafeId;
use Illuminate\Support\Str;

test('super master admin can view subscriptions index', function () {
    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $response = $this->actingAs($super)->get(route('subscriptions.index'));

    $response->assertOk();
});

test('village admin cannot view subscriptions index', function () {
    $state = State::query()->create(['code' => 'TN', 'name_en' => 'Tamil Nadu']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Chennai']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'guest',
        'name_en' => 'Guest Village',
        'is_active' => true,
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);

    $response = $this->actingAs($admin)->get(route('subscriptions.index'));

    $response->assertForbidden();
});

test('super master admin can update a village subscription and creates history row', function () {
    $planA = SubscriptionPlan::factory()->create(['code' => Str::uuid()->toString(), 'max_user_accounts' => 1]);
    $planB = SubscriptionPlan::factory()->create(['code' => Str::uuid()->toString(), 'max_user_accounts' => null]);

    $state = State::query()->create(['code' => 'TS', 'name_en' => 'Test State']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Any']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'demo',
        'name_en' => 'Demo',
        'is_active' => true,
    ]);

    $sub = VillageSubscription::query()->create([
        'village_id' => $village->id,
        'plan_id' => $planA->id,
        'status' => 'active',
        'starts_at' => now()->subMonth()->toDateString(),
        'ends_at' => now()->addMonth()->toDateString(),
    ]);

    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $token = UrlSafeId::encrypt($village->id);

    $response = $this->actingAs($super)->put(route('subscriptions.update', $token), [
        'plan_id' => $planB->id,
        'status' => 'active',
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addYear()->toDateString(),
        'grace_ends_at' => now()->addYear()->addDays(15)->toDateString(),
        'note' => 'Renewed',
    ]);

    $response->assertRedirect();

    $sub->refresh();
    expect($sub->plan_id)->toBe($planB->id);

    expect(SubscriptionHistory::query()->where('village_id', $village->id)->count())->toBe(1);
});
