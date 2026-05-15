<?php

use App\Models\District;
use App\Models\State;
use App\Models\User;
use App\Models\Village;

test('super master admin on main domain does not list village users', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $tithal = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'tithal',
        'name_en' => 'Tithal',
        'is_active' => true,
    ]);
    $kosamba = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
    ]);

    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    User::factory()->create(['role' => 'user', 'village_id' => $tithal->id, 'email' => 'tithal@example.com']);
    User::factory()->create(['role' => 'user', 'village_id' => $kosamba->id, 'email' => 'kosamba@example.com']);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
    $indexUrl = 'http://'.$baseDomain.route('managed-users.index', [], false);

    $this->actingAs($super)
        ->get($indexUrl)
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('requiresVillageContext', true)
            ->where('users.total', 0)
        );
});

test('super master admin on village subdomain only sees that village users', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $tithal = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'tithal',
        'name_en' => 'Tithal',
        'is_active' => true,
    ]);
    $kosamba = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
    ]);

    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    User::factory()->create(['role' => 'user', 'village_id' => $tithal->id, 'email' => 'tithal@example.com']);
    User::factory()->create(['role' => 'user', 'village_id' => $kosamba->id, 'email' => 'kosamba@example.com']);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
    $indexUrl = 'http://tithal.'.$baseDomain.route('managed-users.index', [], false);

    $this->actingAs($super)
        ->get($indexUrl)
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('requiresVillageContext', false)
            ->where('currentVillage.id', $tithal->id)
            ->where('users.total', 1)
            ->where('users.data.0.email', 'tithal@example.com')
        );
});
