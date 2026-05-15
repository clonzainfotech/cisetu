<?php

use App\Models\District;
use App\Models\State;
use App\Models\User;

test('district search returns paginated results', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat', 'is_active' => true]);
    $otherState = State::query()->create(['code' => 'MH', 'name_en' => 'Maharashtra', 'is_active' => true]);

    foreach (range(1, 25) as $index) {
        District::query()->create([
            'state_id' => $state->id,
            'name_en' => "District {$index}",
            'is_active' => true,
        ]);
    }

    District::query()->create([
        'state_id' => $otherState->id,
        'name_en' => 'Palghar',
        'is_active' => true,
    ]);

    $user = User::factory()->create(['is_super_master_admin' => true]);

    $firstPage = $this->actingAs($user)
        ->getJson(route('api.districts.search', ['page' => 1]))
        ->assertOk()
        ->json();

    expect($firstPage['data'])->toHaveCount(20)
        ->and($firstPage['meta']['per_page'])->toBe(20)
        ->and($firstPage['meta']['total'])->toBe(26);

    $searchPage = $this->actingAs($user)
        ->getJson(route('api.districts.search', ['search' => 'Pal']))
        ->assertOk()
        ->json();

    expect($searchPage['data'])->toHaveCount(1)
        ->and($searchPage['data'][0]['name_en'])->toBe('Palghar');
});

test('district search can load a single district by id', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat', 'is_active' => true]);
    $district = District::query()->create([
        'state_id' => $state->id,
        'name_en' => 'Valsad',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.districts.search', ['id' => $district->id]))
        ->assertOk()
        ->json();

    expect($response['data'])->toHaveCount(1)
        ->and($response['data'][0]['name_en'])->toBe('Valsad')
        ->and($response['data'][0]['state_name_en'])->toBe('Gujarat');
});
