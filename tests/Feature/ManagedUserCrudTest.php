<?php

use App\Models\District;
use App\Models\State;
use App\Models\User;
use App\Models\Village;

test('super master admin can update user and change password', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'demo',
        'name_en' => 'Demo',
        'is_active' => true,
    ]);

    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $target = User::factory()->create([
        'role' => 'user',
        'village_id' => $village->id,
        'permissions' => ['personal_tax'],
    ]);

    $response = $this
        ->actingAs($super)
        ->put(route('managed-users.update', $target), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'new-password-123',
            'role' => 'user',
            'village_id' => $village->id,
            'permissions' => ['personal_tax', 'professional_tax'],
        ]);

    $response->assertRedirect(route('managed-users.index'));

    $target->refresh();

    expect($target->name)->toBe('Updated Name')
        ->and($target->email)->toBe('updated@example.com')
        ->and($target->permissions)->toContain('professional_tax');
});

test('super master admin can delete a village user', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'demo2',
        'name_en' => 'Demo Two',
        'is_active' => true,
    ]);

    $super = User::factory()->create([
        'is_super_master_admin' => true,
        'role' => 'super_master_admin',
    ]);

    $target = User::factory()->create([
        'role' => 'user',
        'village_id' => $village->id,
    ]);

    $this
        ->actingAs($super)
        ->delete(route('managed-users.destroy', $target))
        ->assertRedirect(route('managed-users.index'));

    expect(User::query()->find($target->id))->toBeNull();
});

test('village admin cannot update another village admin', function () {
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kelva',
        'name_en' => 'Kelva',
        'is_active' => true,
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);
    $otherAdmin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);

    $originalName = $otherAdmin->name;

    $response = $this
        ->actingAs($admin)
        ->put(route('managed-users.update', $otherAdmin), [
            'name' => 'Blocked',
            'email' => 'blocked-'.$otherAdmin->id.'@example.com',
            'role' => 'user',
            'permissions' => [],
        ]);

    $response->assertRedirect();
    $otherAdmin->refresh();

    expect($otherAdmin->name)->toBe($originalName)
        ->and($otherAdmin->role)->toBe('village_admin');
});

test('user cannot delete themselves', function () {
    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => null]);

    $this
        ->actingAs($admin)
        ->delete(route('managed-users.destroy', $admin))
        ->assertForbidden();
});
