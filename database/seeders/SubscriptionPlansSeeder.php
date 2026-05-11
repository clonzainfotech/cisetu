<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::query()->upsert([
            [
                'code' => 'vikas',
                'name' => 'CI Vikas',
                'description' => 'Single user plan.',
                'is_active' => true,
                'sort_order' => 10,
                'price_per_year_inr' => 0,
                'currency' => 'INR',
                'max_user_accounts' => 1,
            ],
            [
                'code' => 'pragati',
                'name' => 'CI Pragati',
                'description' => 'Unlimited users plan.',
                'is_active' => true,
                'sort_order' => 20,
                'price_per_year_inr' => 0,
                'currency' => 'INR',
                'max_user_accounts' => null,
            ],
        ], ['code'], [
            'name',
            'description',
            'is_active',
            'sort_order',
            'price_per_year_inr',
            'currency',
            'max_user_accounts',
        ]);
    }
}
