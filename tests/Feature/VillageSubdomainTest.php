<?php

use App\Models\District;
use App\Models\State;
use App\Models\Village;

test('registered village subdomain shows the public portal', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosamba',
        'name_en' => 'Kosamba',
        'is_active' => true,
    ]);

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);

    $response = $this->get('http://kosamba.'.$baseDomain.'/');

    $response->assertOk();
    $response->assertSee($village->name_en, false);
});

test('unregistered village subdomain redirects to main domain', function () {
    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);

    $response = $this->get('http://rajkot.'.$baseDomain.'/');

    $response->assertRedirect('http://'.$baseDomain);
});

test('main domain home is not blocked for unknown village names in path', function () {
    $response = $this->get('/');

    $response->assertOk();
});
