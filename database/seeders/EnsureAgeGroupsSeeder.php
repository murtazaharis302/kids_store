<?php

namespace Database\Seeders;

use App\Models\AgeGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnsureAgeGroupsSeeder extends Seeder
{
    public function run()
    {
        $ageGroups = [
            ['name' => '0 to 3 Months', 'slug' => '0-3-months', 'sort' => 1],
            ['name' => '3 to 6 Months', 'slug' => '3-6-months', 'sort' => 2],
            ['name' => '6 to 9 Months', 'slug' => '6-9-months', 'sort' => 3],
            ['name' => '9 to 12 Months', 'slug' => '9-12-months', 'sort' => 4],
            ['name' => '1 to 2 Years', 'slug' => '1-2-years', 'sort' => 5],
            ['name' => '3 to 4 Years', 'slug' => '3-4-years', 'sort' => 6],
            ['name' => '5 to 6 Years', 'slug' => '5-6-years', 'sort' => 7],
            ['name' => '7 to 8 Years', 'slug' => '7-8-years', 'sort' => 8],
            ['name' => '9 to 12 Years', 'slug' => '9-12-years', 'sort' => 9],
        ];

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
