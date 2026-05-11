<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<State>
 */
class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('??'),
            'name_en' => fake()->state(),
            'name_local' => null,
            'is_active' => true,
        ];
    }
}
