<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\State;
use Illuminate\Database\Seeder;

class IndianDistrictsSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * Add districts here state-wise.
         * Key = State code (must exist in `states.code`).
         */
        $data = [
            'GJ' => [
                'Valsad',
                'Surat',
                'Navsari',
            ],
            'MH' => [
                'Palghar',
                'Thane',
                'Mumbai City',
                'Mumbai Suburban',
            ],
        ];

        foreach ($data as $stateCode => $districts) {
            $state = State::query()->where('code', $stateCode)->first();

            if (! $state) {
                continue;
            }

            foreach ($districts as $name) {
                District::query()->firstOrCreate(
                    [
                        'state_id' => $state->id,
                        'name_en' => $name,
                    ],
                    [
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
