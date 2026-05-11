<?php

namespace Database\Factories;

use App\Models\District;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<District>
 */
class DistrictFactory extends Factory
{
    protected $model = District::class;

    public function definition(): array
    {
        return [
            'state_id' => State::factory(),
            'code' => null,
            'name_en' => fake()->city(),
            'name_local' => null,
            'is_active' => true,
        ];
    }
}
