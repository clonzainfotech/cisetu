<?php

namespace Database\Factories;

use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Village>
 */
class VillageFactory extends Factory
{
    protected $model = Village::class;

    public function definition(): array
    {
        return [
            'district_id' => District::factory(),
            'subdomain' => fake()->unique()->slug(2),
            'custom_domain' => null,
            'name_en' => fake()->city(),
            'name_local' => null,
            'census_code' => null,
            'is_active' => true,
        ];
    }
}
