<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            [
                'name' => 'Multi-Family Housing',
                'description' => "In apartments and townhome communities, a pest problem in one unit doesn't stay put for long - it spreads to neighbouring units fast. We help property managers stay ahead of it with reliable service and proactive communication.",
                'common_pests' => ['Rodents', 'Ants & Cockroaches', 'Flies', 'Termites'],
            ],
            [
                'name' => 'Retail Businesses',
                'description' => 'First impressions are everything in retail, and nothing ruins one faster than a pest sighting. High foot traffic and open doors create constant opportunity - we build flexible plans that work around your trading hours.',
                'common_pests' => ['Rodents', 'Ants & Cockroaches', 'Flies', 'Termites'],
            ],
            [
                'name' => 'Restaurants & Food Services',
                'description' => 'In food service, reputation is everything. We work within the fast pace and tight margins of the industry, helping your team reduce the conditions that attract pests in the first place - not just react once they arrive.',
                'common_pests' => ['Rodents', 'Ants & Cockroaches', 'Flies', 'Termites'],
            ],
            [
                'name' => 'Schools & Educational Facilities',
                'description' => 'From preschools to college campuses, keeping pests out protects students, staff and reputation. Service is scheduled to be effective and discreet, without disrupting the school day.',
                'common_pests' => ['Rodents', 'Ants & Cockroaches', 'Flies', 'Termites'],
            ],
        ];

        foreach ($industries as $industry) {
            Industry::updateOrCreate(['name' => $industry['name']], $industry);
        }
    }
}
