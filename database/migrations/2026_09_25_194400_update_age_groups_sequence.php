<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $ageGroups = [
            ['name' => 'Newborn', 'slug' => 'newborn', 'sort_order' => 1],
            ['name' => '0 to 3 Months', 'slug' => '0-3-months', 'sort_order' => 2],
            ['name' => '3 to 6 Months', 'slug' => '3-6-months', 'sort_order' => 3],
            ['name' => '6 to 9 Months', 'slug' => '6-9-months', 'sort_order' => 4],
            ['name' => '9 to 12 Months', 'slug' => '9-12-months', 'sort_order' => 5],
            ['name' => '1 to 2 Years', 'slug' => '1-2-years', 'sort_order' => 6],
            ['name' => '3 to 4 Years', 'slug' => '3-4-years', 'sort_order' => 7],
            ['name' => '5 to 6 Years', 'slug' => '5-6-years', 'sort_order' => 8],
            ['name' => '7 to 8 Years', 'slug' => '7-8-years', 'sort_order' => 9],
            ['name' => '9 to 12 Years', 'slug' => '9-12-years', 'sort_order' => 10],
        ];

        $validSlugs = array_column($ageGroups, 'slug');

        // Deactivate any old or non-standard age groups (e.g. 12-18-months, 18-24-months, 2-3-years)
        DB::table('age_groups')
            ->whereNotIn('slug', $validSlugs)
            ->update(['status' => 0]);

        // Insert or update the 10 target age groups in exact order
        foreach ($ageGroups as $group) {
            $exists = DB::table('age_groups')->where('slug', $group['slug'])->first();
            if ($exists) {
                DB::table('age_groups')
                    ->where('slug', $group['slug'])
                    ->update([
                        'name' => $group['name'],
                        'sort_order' => $group['sort_order'],
                        'status' => 1,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('age_groups')->insert([
                    'name' => $group['name'],
                    'slug' => $group['slug'],
                    'sort_order' => $group['sort_order'],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        // Revert status if needed
    }
};
