<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'key' => 'ratguard-monthly',
                'name' => 'RatGuard Monthly',
                'price' => 260.00,
                'billing_cycle' => 'monthly',
                'description' => 'Keeping your home safe from rodents shouldn\'t be a DIY headache. Our RatGuard Monthly plan provides a proactive, professional "perimeter of protection" to ensure rats stay exactly where they belong—outside.',
                'meta' => json_encode([
                    'frequency_label' => '1 Visit / Month',
                    'visits_per_year' => 12,
                ]),
            ],
            [
                'key' => 'roachguard-360',
                'name' => 'RoachGuard 360',
                'price' => 120.00,
                'billing_cycle' => 'monthly',
                'description' => 'Don\'t wait for an infestation to take over your kitchen. For the price of a couple of coffees, RoachGuard 360 provides year-round protection and peace of mind.',
                'meta' => json_encode([
                    'initial_fee' => 400.00,
                    'frequency_label' => '4 Times / Year',
                    'visits_per_year' => 4,
                ]),
            ],
            [
                'key' => 'antarmor-365',
                'name' => 'AntArmor 365',
                'price' => 156.00,
                'billing_cycle' => 'monthly',
                'description' => 'Stop playing "whack-a-mole" with sugar ants. AntArmor 365 is a year-round subscription designed to destroy the nest at the source and create a long-lasting barrier around your home.',
                'meta' => json_encode([
                    'frequency_label' => '2 Times / Year',
                    'visits_per_year' => 2,
                ]),
            ],
        ];

        foreach ($plans as $plan) {
            $plan['updated_at'] = now();
            $plan['created_at'] = now();

            DB::table('plans')->updateOrInsert(
                ['key' => $plan['key']],
                $plan
            );
        }
    }
}
