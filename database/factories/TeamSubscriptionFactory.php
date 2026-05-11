<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use App\Models\Team;
use App\Models\TeamSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamSubscription>
 */
class TeamSubscriptionFactory extends Factory
{
    protected $model = TeamSubscription::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'plan_id' => SubscriptionPlan::factory(),
            'status' => 'active',
            'starts_at' => now()->toDateString(),
            'ends_at' => now()->addYear()->toDateString(),
            'grace_ends_at' => null,
            'billing_reference' => null,
        ];
    }
}
