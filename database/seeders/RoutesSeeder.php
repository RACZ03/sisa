<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('routes')->insert([
            'id' => 1,
            'code' => 'SANJUDAS1',
            'name' => 'Ruta San Judas Tadeo 1',
            'state_id' => 1, // 'ACTIVE
            'user_id' => 1, // 'SUPERADMIN
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('routes')->insert([
            'id' => 2,
            'code' => 'SANJUDAS2',
            'name' => 'Ruta San Judas Tadeo 2',
            'state_id' => 1, // 'ACTIVE
            'user_id' => 1, // 'SUPERADMIN
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('routes')->insert([
            'id' => 3,
            'code' => 'SANJUDAS3',
            'name' => 'Ruta San Judas Tadeo 3',
            'state_id' => 1, // 'ACTIVE
            'user_id' => 1, // 'SUPERADMIN
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}