<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkaterSeeder extends Seeder
{
    public function run(): void
    {
        $femaleCategory = DB::table('categories')
            ->where('slug', 'best-female-skater')
            ->first();

        $maleCategory = DB::table('categories')
            ->where('slug', 'best-male-skater')
            ->first();

        DB::table('skaters')->insert([
            [
                'name' => 'Clara',
                'photo' => null,
                'category_id' => $femaleCategory->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Linda',
                'photo' => null,
                'category_id' => $femaleCategory->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria',
                'photo' => null,
                'category_id' => $femaleCategory->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Musa',
                'photo' => null,
                'category_id' => $maleCategory->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Juma',
                'photo' => null,
                'category_id' => $maleCategory->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rocky',
                'photo' => null,
                'category_id' => $maleCategory->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
