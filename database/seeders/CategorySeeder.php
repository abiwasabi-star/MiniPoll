<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->updateOrInsert(
            ['slug' => 'best-female-skater'],
            ['name' => 'Best Female Skater']
        );

        DB::table('categories')->updateOrInsert(
            ['slug' => 'best-male-skater'],
            ['name' => 'Best Male Skater']
        );
    }
}
