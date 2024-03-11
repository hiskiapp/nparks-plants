<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Field Groups & Fields
        $fieldGroups = [
            'Name' => [
                [
                    'name' => 'Accepted Scientific Name',
                    'type' => 'text'
                ],
                [
                    'name' => 'Family Name',
                    'type' => 'text'
                ],
                [
                    'name' => 'Species Epithet Type',
                    'type' => 'select'
                ],
                [
                    'name' => 'Synonyms',
                    'type' => 'text'
                ],
                [
                    'name' => 'Common Names',
                    'type' => 'text'
                ],
            ],

            'Biogeography' => [
                [
                    'name' => 'Native Distribution',
                    'type' => 'text'
                ],
                [
                    'name' => 'Native Habitat',
                    'type' => 'select'
                ],
                [
                    'name' => 'Preferred Climate Zone',
                    'type' => 'select'
                ],
                [
                    'name' => 'Local Conservation Status',
                    'type' => 'select'
                ],
                [
                    'name' => 'CITES Protection',
                    'type' => 'select'
                ],
                [
                    'name' => 'Sightings in Singapore',
                    'type' => 'select'
                ]
            ],

            'Classification and Characteristics' => [
                [
                    'name' => 'Plant Division',
                    'type' => 'select'
                ],
                [
                    'name' => 'Plant Growth Form',
                    'type' => 'select'
                ],
                [
                    'name' => 'Lifespan (in Singapore)',
                    'type' => 'text'
                ],
                [
                    'name' => 'Mode of Nutrition',
                    'type' => 'select'
                ],
                [
                    'name' => 'Plant Shape',
                    'type' => 'select'
                ],
                [
                    'name' => 'Maximum Height From',
                    'type' => 'number'
                ],
                [
                    'name' => 'Maximum Height To',
                    'type' => 'number'
                ],
                [
                    'name' => 'Maximum Height Unit',
                    'type' => 'select'
                ],
                [
                    'name' => 'Maximum Plant spread or Crown Width From',
                    'type' => 'number'
                ],
                [
                    'name' => 'Maximum Plant spread or Crown Width To',
                    'type' => 'number'
                ],
                [
                    'name' => 'Maximum Plant spread or Crown Width Unit',
                    'type' => 'select'
                ],
                [
                    'name' => 'Tree or Palm - Trunk Diameter From',
                    'type' => 'number'
                ],
                [
                    'name' => 'Tree or Palm - Trunk Diameter To',
                    'type' => 'number'
                ],
                [
                    'name' => 'Tree or Palm - Trunk Diameter Unit',
                    'type' => 'select'
                ],
                [
                    'name' => 'Plant Ploidy',
                    'type' => 'select'
                ],
                [
                    'name' => 'Chromosome Number',
                    'type' => 'text'
                ]
            ],
        ];

        foreach ($fieldGroups as $groupName => $fields) {
            $fieldGroup = \App\Models\FieldGroup::create([
                'name' => $groupName
            ]);

            foreach ($fields as $field) {
                \App\Models\Field::create([
                    'field_group_id' => $fieldGroup->id,
                    'name' => $field['name'],
                    'type' => $field['type']
                ]);
            }
        }

        // Plants
        $fields = \App\Models\Field::all();
        foreach (range(1, 6000) as $i) {
            $plant = \App\Models\Plant::create([
                'image' => fake()->imageUrl(640, 480, 'plants'),
                'name' => ucwords(fake()->words(3, true)),
                'common_name' => ucwords(fake()->words(3, true)),
            ]);

            foreach ($fields as $field) {
                $skip = fake()->boolean(20);
                if ($field->field_group_id != 1 && $skip) continue;

                $value = null;
                if ($field->type === 'text') {
                    $value = fake()->randomElement([fake()->word(), fake()->sentence()]);
                } elseif ($field->type === 'number') {
                    $value = fake()->randomDigit();
                } elseif ($field->type === 'select') {
                    $value = fake()->word();
                }

                $plant->fields()->attach($field, [
                    'text_value' => gettype($value) === 'string' ? $value : null,
                    'number_value' => gettype($value) === 'integer' ? $value : null,
                ]);
            }
        }
    }
}
