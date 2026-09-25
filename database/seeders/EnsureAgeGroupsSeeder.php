<?php

namespace Database\Seeders;

use App\Models\AgeGroup;
use Illuminate\Database\Seeder;

class EnsureAgeGroupsSeeder extends Seeder
{
    public function run()
    {
        $ageGroups = [
            ['name' => 'Newborn', 'slug' => 'newborn', 'sort' => 1],
            ['name' => '0 to 3 Months', 'slug' => '0-3-months', 'sort' => 2],
            ['name' => '3 to 6 Months', 'slug' => '3-6-months', 'sort' => 3],
            ['name' => '6 to 9 Months', 'slug' => '6-9-months', 'sort' => 4],
            ['name' => '9 to 12 Months', 'slug' => '9-12-months', 'sort' => 5],
            ['name' => '1 to 2 Years', 'slug' => '1-2-years', 'sort' => 6],
            ['name' => '3 to 4 Years', 'slug' => '3-4-years', 'sort' => 7],
            ['name' => '5 to 6 Years', 'slug' => '5-6-years', 'sort' => 8],
            ['name' => '7 to 8 Years', 'slug' => '7-8-years', 'sort' => 9],
            ['name' => '9 to 10 Years', 'slug' => '9-10-years', 'sort' => 10],
            ['name' => '11 to 12 Years', 'slug' => '11-12-years', 'sort' => 11],
        ];

        $validSlugs = collect($ageGroups)->pluck('slug')->toArray();

        // Deactivate old/unwanted age groups (e.g. 9-12-years, 12-18-months, 18-24-months, 2-3-years, 4-5-years)
        AgeGroup::whereNotIn('slug', $validSlugs)->update(['status' => false]);

        foreach ($ageGroups as $group) {
            AgeGroup::updateOrCreate(
                ['slug' => $group['slug']],
                [
                    'name' => $group['name'],
                    'sort_order' => $group['sort'],
                    'status' => true,
                ]
            );
        }
    }
}
