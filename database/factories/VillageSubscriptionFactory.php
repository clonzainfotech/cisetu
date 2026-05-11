<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use App\Models\Village;
use App\Models\VillageSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageSubscription>
 */
class VillageSubscriptionFactory extends Factory
{
    protected $model = VillageSubscription::class;

    public function definition(): array
    {
        return [
            'village_id' => Village::factory(),
            'plan_id' => SubscriptionPlan::factory(),
            'status' => 'active',
            'starts_at' => now()->toDateString(),
            'ends_at' => now()->addYear()->toDateString(),
            'grace_ends_at' => null,
            'billing_reference' => null,
        ];
    }
}
