<?php

namespace Database\Seeders;

use App\Models\Pest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PestSeeder extends Seeder
{
    public function run(): void
    {
        $pests = [
            [
                'name' => 'Cockroaches',
                'description' => 'Cockroaches carry disease-causing organisms such as salmonella, and an infestation left untreated spreads fast. Our technicians use a specially designed gel formulation rather than blanket spraying — sprays and fumigation tend to scatter roaches deeper into wall cavities and into neighbouring units, making the problem harder to solve, not easier.',
                'photo_path' => 'images/pests/cockroaches.jpg',
                'featured' => true,
            ],
            [
                'name' => 'Ants',
                'description' => 'Trail-and-nest colonies drawn to kitchens and paving — treated at the source, not just the visible trail.',
                'photo_path' => 'images/pests/ants.jpg',
            ],
            [
                'name' => 'Bed Bugs',
                'description' => 'Fast-spreading in shared walls and furniture. Thorough inspection before treatment is essential.',
                'photo_path' => 'images/pests/bedbugs.jpg',
            ],
            [
                'name' => 'Rodents',
                'description' => 'Rats and mice damage wiring and insulation and carry disease. Bait stations create a lasting perimeter.',
                'photo_path' => 'images/pests/rodents.jpg',
            ],
            [
                'name' => 'Silverfish',
                'description' => 'Moisture-loving pests that damage paper, fabric and stored goods in quiet, undisturbed areas.',
                'photo_path' => 'images/pests/silverfish.jpg',
            ],
            [
                'name' => 'Bee Removal',
                'description' => 'Safe, humane relocation of hives from roof spaces, walls and outbuildings.',
                'photo_path' => 'images/pests/bees.jpg',
            ],
            [
                'name' => 'Bird & Pigeon Proofing',
                'description' => 'Netting and spike systems that keep roosting birds off ledges and roof lines without harm.',
                'photo_path' => 'images/pests/pigeons.jpg',
            ],
            [
                'name' => 'Fleas',
                'description' => 'Treatment for yards, kennels and interiors where pets bring pests indoors.',
                'photo_path' => 'images/pests/fleas.jpg',
            ],
            [
                'name' => 'Ticks',
                'description' => 'Treatment for yards, kennels and interiors where pets bring pests indoors.',
                'photo_path' => 'images/pests/ticks.jpg',
            ],
            [
                'name' => 'Scorpions',
                'description' => 'Perimeter and crevice treatment for properties near natural veld or rockeries.',
                'photo_path' => 'images/pests/scorpions.jpg',
            ],
            [
                'name' => 'Spiders',
                'description' => 'Web and harbourage treatment around eaves, garages and undisturbed storage areas.',
                'photo_path' => 'images/pests/spiders.jpg',
            ],
            [
                'name' => 'Wasps',
                'description' => 'Nest removal handled at a safe distance, with entry-point treatment to discourage rebuilding.',
                'photo_path' => 'images/pests/wasps.jpg',
            ],
            [
                'name' => 'Termites',
                'description' => 'Structural pests requiring soil and timber treatment before damage reaches load-bearing areas.',
                'photo_path' => 'images/pests/termites.jpg',
            ],
        ];

        foreach ($pests as $pest) {
            Pest::updateOrCreate(
                ['slug' => Str::slug($pest['name'])],
                [
                    'name' => $pest['name'],
                    'description' => $pest['description'],
                    'photo_path' => $pest['photo_path'],
                    'category' => 'residential',
                    'featured' => $pest['featured'] ?? false,
                ]
            );
        }
    }
}
