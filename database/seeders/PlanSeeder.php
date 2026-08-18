<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(['key' => 'ratguard'], [
            'name' => 'RatGuard Monthly',
            'price' => 260.00,
            'billing_cycle' => 'monthly',
            'description' => 'A proactive perimeter of protection so rats stay exactly where they belong — outside.',
            'meta' => [
                'includes' => [
                    'Professional installation of 4 tamper-resistant bait stations around your property',
                    'Monthly technician visit to inspect, replenish and monitor for new activity',
                    'Tamper-resistant stations, safe around children and pets',
                    'Detailed report after every visit',
                ],
                'terms' => '1 visit/month · VAT incl. · No refill fees',
            ],
        ]);

        Plan::updateOrCreate(['key' => 'roachguard'], [
            'name' => 'RoachGuard 360',
            'price' => 120.00,
            'billing_cycle' => 'monthly',
            'description' => "Year-round protection, billed monthly. We manage the lifecycle of the pest so it doesn't come back.",
            'meta' => [
                'includes' => [
                    '4 professional cockroach treatments per year, once every 90 days',
                    'Breaks the breeding cycle across every season',
                    'Member call-out rate of R90 for extra visits between scheduled treatments',
                    'Professional-grade gels and sprays — not over-the-counter cans',
                ],
                'terms' => '4 treatments/yr · Excess visits R90 · 30-day cancel notice',
                'featured' => true,
            ],
        ]);

        Plan::updateOrCreate(['key' => 'antarmor'], [
            'name' => 'AntArmor 365',
            'price' => 156.00,
            'billing_cycle' => 'monthly',
            'description' => 'Stop playing whack-a-mole with sugar ants. We destroy the nest at the source, not just the trail.',
            'meta' => [
                'includes' => [
                    '2 bi-annual deep treatments timed to spring and summer surges',
                    'Perimeter shielding around exterior foundations and entry points',
                    'Priority emergency call-out rate of R150 between visits',
                    'Garden colony mapping before nests migrate indoors',
                ],
                'terms' => '2 visits/yr · Extra callouts R150 · Ant-free 30-day guarantee',
            ],
        ]);
    }
}
