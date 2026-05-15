<?php

use App\Models\District;
use App\Models\Home;
use App\Models\Shop;
use App\Models\State;
use App\Models\SubscriptionHistory;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Village;

test('super master admin can delete a village', function () {
    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Surat']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
    ]);

    $response = $this->actingAs($super)->delete(route('villages.destroy', $village));

    $response->assertRedirect(route('villages.index'));
    expect(Village::query()->find($village->id))->toBeNull();
});

test('deleted village subdomain redirects to main domain', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Surat']);
    Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
    ])->delete();

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);

    $response = $this->get('http://kosmba.'.$baseDomain.'/');

    $response->assertRedirect('http://'.$baseDomain);
});

test('deleting a village removes homes shops users and subscription history', function () {
    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Surat']);
    $plan = SubscriptionPlan::factory()->create();
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
        'subscription_plan_id' => $plan->id,
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);
    $staff = User::factory()->create(['role' => 'user', 'village_id' => $village->id]);

    $home = Home::query()->create([
        'village_id' => $village->id,
        'user_id' => $staff->id,
        'property_no' => 'H-001',
        'total' => 100,
    ]);

    $shop = Shop::query()->create([
        'village_id' => $village->id,
        'user_id' => $staff->id,
        'reg_no' => 'S-001',
        'name' => 'General Store',
        'total' => 200,
    ]);

    SubscriptionHistory::query()->create([
        'village_id' => $village->id,
        'plan_id' => $plan->id,
        'event_type' => 'activated',
        'performed_by_user_id' => $super->id,
    ]);

    $this->actingAs($super)->delete(route('villages.destroy', $village));

    expect(Village::query()->find($village->id))->toBeNull()
        ->and(Home::query()->find($home->id))->toBeNull()
        ->and(Shop::query()->find($shop->id))->toBeNull()
        ->and(User::query()->find($admin->id))->toBeNull()
        ->and(User::query()->find($staff->id))->toBeNull()
        ->and(SubscriptionHistory::query()->where('village_id', $village->id)->count())->toBe(0);
});

test('village admin cannot delete a village from their subdomain', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Surat']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
    $url = 'http://kosmba.'.$baseDomain.route('villages.destroy', $village, false);

    $response = $this->actingAs($admin)->delete($url);

    $response->assertForbidden();
    expect(Village::query()->find($village->id))->not->toBeNull();
});
