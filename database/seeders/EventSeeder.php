<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            'id' => 1,
            'code' => 'LOAD',
            'name' => 'Carga',
            'state_id' => 1, // 'ACTIVE
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('events')->insert([
            'id' => 2,
            'code' => 'DEBIT',
            'name' => 'Débito',
            'state_id' => 1, // 'ACTIVE
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
