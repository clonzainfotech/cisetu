<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

test('registration routes are disabled', function () {
    expect(Route::has('register'))->toBeFalse();
    expect(Route::has('register.store'))->toBeFalse();

    $this->get('/register')->assertNotFound();
    $this->post('/register')->assertNotFound();
});

test('new users cannot self register', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertGuest();

    expect(User::where('email', 'test@example.com')->exists())->toBeFalse();
});
