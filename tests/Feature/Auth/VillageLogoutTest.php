<?php

use App\Models\District;
use App\Models\State;
use App\Models\User;
use App\Models\Village;

test('village admin logout on village subdomain redirects to village portal home', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'rajkot',
        'name_en' => 'Rajkot',
        'is_active' => true,
    ]);

    $user = User::factory()->create([
        'village_id' => $village->id,
        'role' => 'village_admin',
    ]);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);

    $response = $this->actingAs($user)
        ->post('http://rajkot.'.$baseDomain.'/logout');

    $this->assertGuest();
    $response->assertRedirect('http://rajkot.'.$baseDomain.'/');
});

test('logout on main domain redirects to login', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(url('/login'));
});
