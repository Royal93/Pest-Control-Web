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
            'description' => 'A proactive perimeter of protection so rats stay exactly where they belong - outside.',
            'meta' => [
                'includes' => [
                    'Professional installation of 4 tamper-resistant bait stations around your property',
                    'Monthly technician visit to inspect, replenish and monitor for new activity',
                    'Tamper-resistant stations, safe around children and pets',
                    'Detailed report after every visit',
                ],
                'terms' => '1 visit/month · VAT incl. · No refill fees',
                'why' => "Rats are more than just a nuisance; they can damage wiring, compromise insulation, and carry diseases. By choosing a monthly service, you aren't just reacting to a problem - you're preventing one. Our consistent monitoring ensures that if a rodent population tries to move in, we stop them before they reach your front door.",
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
                    'Professional-grade gels and sprays - not over-the-counter cans',
                ],
                'terms' => '4 treatments/yr · Excess visits R90 · 30-day cancel notice',
                'featured' => true,
                'math' => "Your subscription ensures your home is treated by a professional every quarter. Because you're a subscriber, any extra treatments you might want are billed at our cost price of R90, rather than a full call-out fee.",
                'why_reasons' => [
                    'Budget Friendly: no big surprise invoices - just a small, steady monthly amount.',
                    "Proactive vs. Reactive: most people call a pest controller when it's too late. We stop the problem before it starts.",
                    'Professional Grade: we use specialised gels and sprays that are far more effective (and safer) than over-the-counter cans.',
                ],
                'fine_print' => [
                    'Billing: R120 per month via secure automated card payment.',
                    'Service Interval: one treatment every 90 days.',
                    'Excess Treatments: anything beyond the 4 scheduled annual visits is invoiced at R90 per visit, payable at time of service.',
                    "Cancellation: 30 days' notice required.",
                ],
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
                'value_proposition' => [
                    ['label' => 'Standard Single Treatment', 'value' => 'R1,260 + VAT'],
                    ['label' => 'Two Yearly Treatments', 'value' => 'R2,520 + VAT'],
                    ['label' => 'AntArmor 365 Subscription', 'value' => 'R156/mo - R1,872 total'],
                ],
                'savings' => 'R648 saved per year versus paying for two standard treatments separately.',
                'why_reasons' => [
                    'Breaks the Breeding Cycle: ants are seasonal - treating twice a year means the colony never gets the chance to fully rebuild.',
                    'Pet & Family Safe: targeted gel baits and low-toxicity perimeter sprays focus on ant biology, not drenching your home in chemicals.',
                    'Financial Peace of Mind: you know exactly what pest control costs every month - no surprise bills when the ants decide to move in.',
                ],
                'guarantee' => "The Ant-Free Guarantee: if you see a significant ant trail inside your home within 30 days of a scheduled treatment, we'll come back and spot-treat that area for free.",
            ],
        ]);
    }
}
