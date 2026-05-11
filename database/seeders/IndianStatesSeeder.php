<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class IndianStatesSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            ['code' => 'AP', 'name_en' => 'Andhra Pradesh'],
            ['code' => 'AR', 'name_en' => 'Arunachal Pradesh'],
            ['code' => 'AS', 'name_en' => 'Assam'],
            ['code' => 'BR', 'name_en' => 'Bihar'],
            ['code' => 'CT', 'name_en' => 'Chhattisgarh'],
            ['code' => 'GA', 'name_en' => 'Goa'],
            ['code' => 'GJ', 'name_en' => 'Gujarat'],
            ['code' => 'HR', 'name_en' => 'Haryana'],
            ['code' => 'HP', 'name_en' => 'Himachal Pradesh'],
            ['code' => 'JH', 'name_en' => 'Jharkhand'],
            ['code' => 'KA', 'name_en' => 'Karnataka'],
            ['code' => 'KL', 'name_en' => 'Kerala'],
            ['code' => 'MP', 'name_en' => 'Madhya Pradesh'],
            ['code' => 'MH', 'name_en' => 'Maharashtra'],
            ['code' => 'MN', 'name_en' => 'Manipur'],
            ['code' => 'ML', 'name_en' => 'Meghalaya'],
            ['code' => 'MZ', 'name_en' => 'Mizoram'],
            ['code' => 'NL', 'name_en' => 'Nagaland'],
            ['code' => 'OR', 'name_en' => 'Odisha'],
            ['code' => 'PB', 'name_en' => 'Punjab'],
            ['code' => 'RJ', 'name_en' => 'Rajasthan'],
            ['code' => 'SK', 'name_en' => 'Sikkim'],
            ['code' => 'TN', 'name_en' => 'Tamil Nadu'],
            ['code' => 'TG', 'name_en' => 'Telangana'],
            ['code' => 'TR', 'name_en' => 'Tripura'],
            ['code' => 'UP', 'name_en' => 'Uttar Pradesh'],
            ['code' => 'UT', 'name_en' => 'Uttarakhand'],
            ['code' => 'WB', 'name_en' => 'West Bengal'],
        ];

        State::query()->upsert(
            collect($states)->map(fn (array $s) => [
                ...$s,
                'name_local' => null,
                'is_active' => true,
            ])->all(),
            ['code'],
            ['name_en', 'name_local', 'is_active'],
        );
    }
}
