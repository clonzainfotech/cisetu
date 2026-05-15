<?php

use App\Models\User;

test('appearance settings route is not available', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/settings/appearance')
        ->assertNotFound();
});

test('app layout forces light mode and clears appearance cookie', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withCookie('appearance', 'dark')
        ->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('color-scheme: light', false);
    $response->assertSee("root.classList.remove('dark')", false);
    $response->assertSee("localStorage.removeItem('appearance')", false);
    $response->assertDontSee('@class([\'dark\'', false);
});
