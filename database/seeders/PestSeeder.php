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
                'description' => 'Cockroaches carry disease-causing organisms such as salmonella, and an infestation left untreated spreads fast. Our technicians use a specially designed gel formulation rather than blanket spraying - sprays and fumigation tend to scatter roaches deeper into wall cavities and into neighbouring units, making the problem harder to solve, not easier.',
                'photo_path' => 'images/pests/cockroaches.png',
                'featured' => true,
                'infestation_signs' => 'Small dark droppings, egg cases (oothecae) tucked into cracks and crevices, a persistent musty odour, and roaches seen moving during the day - since they are normally nocturnal, daytime sightings usually mean a larger population than expected.',
                'health_risks' => 'Cockroaches can carry bacteria such as Salmonella and E. coli on their bodies and legs, and their droppings and shed skins are a recognised trigger for asthma and allergic reactions, particularly in children.',
                'business_impact' => 'A single sighting in a restaurant or retail space can trigger a failed health inspection or a food-safety closure order, and the reputational damage - especially if it reaches a review site - often outlasts the infestation itself.',
            ],
            [
                'name' => 'Ants',
                'description' => 'Trail-and-nest colonies drawn to kitchens and paving - treated at the source, not just the visible trail.',
                'photo_path' => 'images/pests/ants.png',
                'infestation_signs' => 'Visible trails along skirting boards, countertops, or paving, small entry points near doors and windows, and satellite nests in wall voids or garden beds.',
                'health_risks' => 'Most household ants pose low direct health risk, though some species can bite or sting, and ants moving across food surfaces can transfer bacteria picked up from bins or drains.',
                'business_impact' => 'In food service, ant trails near prep areas are a common health-inspection citation; in retail, customers tend to associate any visible ants with poor hygiene, regardless of the actual cause.',
            ],
            [
                'name' => 'Bed Bugs',
                'description' => 'Fast-spreading in shared walls and furniture. Thorough inspection before treatment is essential.',
                'photo_path' => 'images/pests/bedbugs.png',
                'infestation_signs' => 'Small rust-coloured spots on sheets (droppings or crushed bugs), shed skins, a faint sweet odour, and bite marks that often appear in a line or cluster after sleeping.',
                'health_risks' => 'Bites cause itching and welts, and in some people trigger an allergic reaction. Prolonged infestations have also been linked to secondary skin infections from scratching and disrupted sleep.',
                'business_impact' => 'For hotels, guesthouses, and multi-family housing, a confirmed case usually means an immediate room closure and professional treatment cost - and if it reaches a review site, reputational damage that is genuinely hard to reverse.',
            ],
            [
                'name' => 'Rodents',
                'description' => 'Rats and mice damage wiring and insulation and carry disease. Bait stations create a lasting perimeter.',
                'photo_path' => 'images/pests/rodents.png',
                'infestation_signs' => 'Droppings, gnaw marks on wiring, wood, or packaging, greasy rub marks along walls, and scratching sounds in ceilings or wall voids, usually at night.',
                'health_risks' => 'Rats and mice can carry pathogens linked to conditions such as salmonellosis and leptospirosis, mainly through droppings, urine, or contaminated surfaces and food.',
                'business_impact' => 'Gnawed wiring is a genuine fire risk, and droppings found near stock or food-prep areas are one of the fastest routes to a failed health inspection or a product recall.',
            ],
            [
                'name' => 'Silverfish',
                'description' => 'Moisture-loving pests that damage paper, fabric and stored goods in quiet, undisturbed areas.',
                'photo_path' => 'images/pests/silverfish.png',
                'infestation_signs' => 'Small, silvery, fish-shaped insects found in bathrooms, basements, or storage areas, along with irregular holes or yellowish staining on paper, cardboard, and fabric.',
                'health_risks' => 'Silverfish are not known to bite or transmit disease to humans - the concern with this pest is property and stock damage rather than health.',
                'business_impact' => 'Businesses storing paper records, packaging, or archived stock can lose inventory and documents to silverfish damage quietly, often before anyone notices.',
            ],
            [
                'name' => 'Bee Removal',
                'description' => 'Safe, humane relocation of hives from roof spaces, walls and outbuildings.',
                'photo_path' => 'images/pests/bees.png',
                'infestation_signs' => 'Visible hive activity in roof spaces, wall cavities, or eaves, and a noticeable increase in bee traffic around one specific entry point.',
                'health_risks' => 'Stings cause pain and swelling, and are a serious concern for anyone with a bee allergy - in rare cases this can progress to a medical emergency.',
                'business_impact' => 'An active hive near a public entrance or outdoor seating area is a real liability concern; a sting incident involving a customer or staff member can lead to complaints or, in serious cases, legal exposure.',
            ],
            [
                'name' => 'Bird & Pigeon Proofing',
                'description' => 'Netting and spike systems that keep roosting birds off ledges and roof lines without harm.',
                'photo_path' => 'images/pests/pigeons.png',
                'infestation_signs' => 'Droppings accumulating on ledges, signage, or walkways, nesting material in gutters or roof spaces, and birds repeatedly roosting in the same spots.',
                'health_risks' => 'Accumulated droppings can harbour fungal spores linked to respiratory illness in rare cases, and create slip hazards on walkways.',
                'business_impact' => 'Dropping-stained signage or entrances create an immediate poor first impression for customers, and buildup can corrode roofing materials and block gutters over time.',
            ],
            [
                'name' => 'Fleas',
                'description' => 'Treatment for yards, kennels and interiors where pets bring pests indoors.',
                'photo_path' => 'images/pests/fleas.png',
                'infestation_signs' => 'Pets scratching more than usual, small dark specks ("flea dirt") in pet bedding, and bites around the ankles of people spending time in an affected room or yard.',
                'health_risks' => 'Bites cause itching and, in sensitive people, allergic skin reactions. Fleas can also transmit tapeworm to pets.',
                'business_impact' => 'For boarding kennels, grooming salons, or pet-friendly retail spaces, a single flea complaint can spread quickly among clients with pets and damage trust in the business\'s hygiene standards.',
            ],
            [
                'name' => 'Ticks',
                'description' => 'Treatment for yards, kennels and interiors where pets bring pests indoors.',
                'photo_path' => 'images/pests/ticks.png',
                'infestation_signs' => 'Ticks found on pets or people after time outdoors - often in long grass or garden edges - and small bites that persist for days.',
                'health_risks' => 'Ticks can transmit bacterial infections such as tick-bite fever through their bite, with symptoms that can include fever, rash, and fatigue.',
                'business_impact' => 'For kennels, farms, or venues near open land, an unmanaged tick population is both a liability concern and a recurring health issue for pets and staff.',
            ],
            [
                'name' => 'Scorpions',
                'description' => 'Perimeter and crevice treatment for properties near natural veld or rockeries.',
                'photo_path' => 'images/pests/scorpions.png',
                'infestation_signs' => 'Sightings under rocks, logs, or debris near the property, and occasional indoor sightings in dry, undisturbed areas like garages or storage rooms.',
                'health_risks' => 'Most stings cause localised pain and swelling. A small number of species can cause a more serious reaction, particularly in children or allergic individuals - seek medical attention if symptoms are severe.',
                'business_impact' => 'For properties near open veld, guest safety around outdoor seating or storage areas is the main concern - a sting incident involving a guest or staff member is a liability worth avoiding proactively.',
            ],
            [
                'name' => 'Spiders',
                'description' => 'Web and harbourage treatment around eaves, garages and undisturbed storage areas.',
                'photo_path' => 'images/pests/spiders.png',
                'infestation_signs' => 'Visible webs in eaves, garages, or undisturbed corners, and egg sacs tucked into storage areas.',
                'health_risks' => 'Most spiders are harmless to humans. A small number of species found locally can cause a more serious bite reaction, though bites of that kind are uncommon.',
                'business_impact' => 'Cobwebs in entryways or display areas create an unkempt impression for customers, even when the spiders themselves pose no real danger.',
            ],
            [
                'name' => 'Wasps',
                'description' => 'Nest removal handled at a safe distance, with entry-point treatment to discourage rebuilding.',
                'photo_path' => 'images/pests/wasps.png',
                'infestation_signs' => 'A visible nest under eaves, in wall cavities, or in garden structures, and increased wasp activity around one area - especially near food or waste bins.',
                'health_risks' => 'Stings cause pain and swelling; multiple stings or an allergic reaction can become medically serious. Unlike bees, wasps can sting more than once.',
                'business_impact' => 'An active nest near an entrance, outdoor seating, or a waste area is a genuine safety liability - one sting incident involving a customer or staff member can lead to complaints or worse.',
            ],
            [
                'name' => 'Termites',
                'description' => 'Structural pests requiring soil and timber treatment before damage reaches load-bearing areas.',
                'photo_path' => 'images/pests/termites.png',
                'infestation_signs' => 'Mud tubes along foundations or walls, hollow-sounding timber, discarded wings near windowsills, and sagging or damaged skirting or structural wood.',
                'health_risks' => 'Termites don\'t pose a direct health risk to humans - the risk with this pest is entirely structural.',
                'business_impact' => 'Termite damage is often invisible until it\'s severe. Structural repairs can cost significantly more than early treatment, and undisclosed damage can also affect property value and insurance claims.',
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
                    'infestation_signs' => $pest['infestation_signs'],
                    'health_risks' => $pest['health_risks'],
                    'business_impact' => $pest['business_impact'],
                ]
            );
        }
    }
}
