<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('technologies')->insert([
            'id' => 1,
            'code' => 'HFC',
            'name' => 'HFC',
            'description' => 'Hybrid Fiber Coaxial',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('technologies')->insert([
            'id' => 2,
            'code' => 'GPON',
            'name' => 'GPON',
            'description' => 'Gigabit Passive Optical Network',
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
