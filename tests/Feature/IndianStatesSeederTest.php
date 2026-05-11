<?php

use App\Models\State;
use Database\Seeders\IndianStatesSeeder;

test('indian states seeder inserts states', function () {
    $this->seed(IndianStatesSeeder::class);

    expect(State::count())->toBeGreaterThanOrEqual(28);
    expect(State::where('code', 'GJ')->where('name_en', 'Gujarat')->exists())->toBeTrue();
    expect(State::where('code', 'MH')->where('name_en', 'Maharashtra')->exists())->toBeTrue();
});
