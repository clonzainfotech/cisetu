<?php

use App\Models\District;
use App\Models\State;
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
