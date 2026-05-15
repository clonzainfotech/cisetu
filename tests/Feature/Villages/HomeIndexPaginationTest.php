<?php

use App\Models\District;
use App\Models\Home;
use App\Models\State;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Village;

test('homes index includes paginator navigation urls', function () {
    $plan = SubscriptionPlan::factory()->create();
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Surat']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
        'subscription_plan_id' => $plan->id,
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);

    foreach (range(1, 12) as $i) {
        Home::query()->create([
            'village_id' => $village->id,
            'user_id' => $admin->id,
            'property_no' => sprintf('P-%03d', $i),
            'total' => 100,
        ]);
    }

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
    $indexUrl = 'http://kosmba.'.$baseDomain.route('homes.index', ['limit' => 10], false);

    $response = $this->actingAs($admin)->get($indexUrl);

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('villages/homes/Index')
        ->where('homes.total', 12)
        ->where('homes.from', 1)
        ->where('homes.to', 10)
        ->where('filters.limit', 10)
        ->where('homes.prev_page_url', null)
        ->has('homes.next_page_url')
        ->where('homes.next_page_url', fn ($url) => is_string($url) && str_contains($url, 'limit=10'))
        ->has('homes.links', 4)
    );
});

test('homes paginator keeps limit when visiting next page', function () {
    $plan = SubscriptionPlan::factory()->create();
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Surat']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
        'subscription_plan_id' => $plan->id,
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);

    foreach (range(1, 25) as $i) {
        Home::query()->create([
            'village_id' => $village->id,
            'user_id' => $admin->id,
            'property_no' => sprintf('L-%03d', $i),
            'total' => 100,
        ]);
    }

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
    $indexUrl = 'http://kosmba.'.$baseDomain.route('homes.index', ['limit' => 10], false);

    $response = $this->actingAs($admin)->get($indexUrl);

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->where('filters.limit', 10)
        ->where('homes.per_page', 10)
        ->where('homes.next_page_url', fn ($url) => is_string($url) && str_contains($url, 'limit=10') && str_contains($url, 'page=2'))
    );

    $nextUrl = 'http://kosmba.'.$baseDomain.route('homes.index', ['limit' => 10, 'page' => 2], false);

    $pageTwo = $this->actingAs($admin)->get($nextUrl);

    $pageTwo->assertOk();
    $pageTwo->assertInertia(fn ($page) => $page
        ->where('filters.limit', 10)
        ->where('homes.per_page', 10)
        ->where('homes.current_page', 2)
    );
});

test('homes index includes personal tax on each row', function () {
    $plan = SubscriptionPlan::factory()->create();
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Surat']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
        'subscription_plan_id' => $plan->id,
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);

    Home::query()->create([
        'village_id' => $village->id,
        'user_id' => $admin->id,
        'property_no' => '101',
        'house_no' => '1',
        'owner' => 'Test Owner',
        'occupant' => 'Test Owner',
        'address' => 'Test Address',
        'total' => 2790.50,
    ]);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
    $indexUrl = 'http://kosmba.'.$baseDomain.route('homes.index', [], false);

    $this->actingAs($admin)
        ->get($indexUrl)
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('homes.total', 1)
            ->where('homes.data.0.total', fn ($value) => (float) $value === 2790.5)
        );
});
