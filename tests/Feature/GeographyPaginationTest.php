<?php

use App\Models\District;
use App\Models\State;
use App\Models\User;
use App\Models\Village;

test('super master admin can paginate and search villages', function () {
    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);

    Village::query()->create(['district_id' => $district->id, 'subdomain' => 'alpha', 'name_en' => 'Alpha', 'is_active' => true]);
    Village::query()->create(['district_id' => $district->id, 'subdomain' => 'beta', 'name_en' => 'Beta', 'is_active' => true]);
    Village::query()->create(['district_id' => $district->id, 'subdomain' => 'gamma', 'name_en' => 'Gamma', 'is_active' => true]);

    $response = $this
        ->actingAs($super)
        ->get(route('villages.index', ['search' => 'alp', 'limit' => 10]));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('villages/Index')
        ->where('filters.search', 'alp')
        ->where('filters.limit', 10)
        ->has('villages.data', 1)
    );
});

test('super master admin can paginate and search states and districts', function () {
    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $gj = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat', 'is_active' => true]);
    $mh = State::query()->create(['code' => 'MH', 'name_en' => 'Maharashtra', 'is_active' => true]);

    District::query()->create(['state_id' => $gj->id, 'name_en' => 'Valsad', 'is_active' => true]);
    District::query()->create(['state_id' => $mh->id, 'name_en' => 'Palghar', 'is_active' => true]);

    $statesResponse = $this
        ->actingAs($super)
        ->get(route('states.index', ['search' => 'Guj', 'limit' => 10]));

    $statesResponse->assertOk();
    $statesResponse->assertInertia(fn ($page) => $page
        ->component('geography/States')
        ->where('filters.search', 'Guj')
        ->where('filters.limit', 10)
        ->has('states.data', 1)
    );

    $districtsResponse = $this
        ->actingAs($super)
        ->get(route('districts.index', ['search' => 'Pal', 'limit' => 10]));

    $districtsResponse->assertOk();
    $districtsResponse->assertInertia(fn ($page) => $page
        ->component('geography/Districts')
        ->where('filters.search', 'Pal')
        ->where('filters.limit', 10)
        ->has('districts.data', 1)
    );
});
