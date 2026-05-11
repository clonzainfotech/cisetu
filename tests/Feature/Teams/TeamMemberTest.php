<?php

use Illuminate\Support\Facades\Route;

test('team member routes are disabled', function () {
    expect(Route::has('teams.members.update'))->toBeFalse();
    expect(Route::has('teams.members.destroy'))->toBeFalse();
});
