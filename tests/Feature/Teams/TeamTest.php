<?php

use Illuminate\Support\Facades\Route;

test('team routes are disabled', function () {
    expect(Route::has('teams.index'))->toBeFalse();
    expect(Route::has('teams.store'))->toBeFalse();
    expect(Route::has('teams.edit'))->toBeFalse();
    expect(Route::has('teams.update'))->toBeFalse();
    expect(Route::has('teams.destroy'))->toBeFalse();
    expect(Route::has('teams.switch'))->toBeFalse();

    $this->get('/settings/teams')->assertNotFound();
});
