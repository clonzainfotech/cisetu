<?php

use App\Models\District;
use App\Models\State;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Village;
use App\Models\VillageSubscription;

test('super master admin can create a village admin', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'tithal',
        'name_en' => 'Tithal',
        'is_active' => true,
    ]);

    $plan = SubscriptionPlan::factory()->create(['max_user_accounts' => null]);
    VillageSubscription::query()->create([
        'village_id' => $village->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addYear()->toDateString(),
    ]);

    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $response = $this
        ->actingAs($super)
        ->post(route('managed-users.store'), [
            'name' => 'New Admin',
            'email' => 'new.admin@example.com',
            'password' => 'password',
            'role' => 'village_admin',
            'village_id' => $village->id,
        ]);

    $response->assertRedirect(route('managed-users.index'));

    expect(User::where('email', 'new.admin@example.com')->where('role', 'village_admin')->exists())->toBeTrue();
});

test('village admin cannot create more users than subscription max_user_accounts', function () {
    $state = State::query()->create(['code' => 'MH', 'name_en' => 'Maharashtra']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Palghar']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kelva',
        'name_en' => 'Kelva',
        'is_active' => true,
    ]);

    $plan = SubscriptionPlan::factory()->create(['max_user_accounts' => 1]);
    VillageSubscription::query()->create([
        'village_id' => $village->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addYear()->toDateString(),
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);
    User::factory()->create(['role' => 'user', 'village_id' => $village->id]);

    $response = $this
        ->actingAs($admin)
        ->post(route('managed-users.store'), [
            'name' => 'Blocked User',
            'email' => 'blocked.user@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

    $response->assertForbidden();
});

test('regular user cannot create users', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this
        ->actingAs($user)
        ->post(route('managed-users.store'), [
            'name' => 'Nope',
            'email' => 'nope@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

    $response->assertForbidden();
});
