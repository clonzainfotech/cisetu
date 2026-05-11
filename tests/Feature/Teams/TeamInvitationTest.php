<?php

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

test('team invitation routes are disabled', function () {
    Notification::fake();

    expect(Route::has('teams.invitations.store'))->toBeFalse();
    expect(Route::has('teams.invitations.destroy'))->toBeFalse();
});

test('team invitation accept route is disabled', function () {
    $this->get('/invitations/any/accept')->assertRedirect(route('login'));
});
