<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// import model user
use App\Models\User;
use App\Models\Role;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get role code TECHNICAL

        DB::table('routes')->insert([
            'id' => 1,
            'code' => 'RUTA-01',
            'name' => 'RUTA-01',
            'state_id' => 1,
            'user_id' => 2, // 'TECHNICAL
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('routes')->insert([
            'id' => 2,
            'code' => 'RUTA-02',
            'name' => 'RUTA-02',
            'state_id' => 1,
            'user_id' => 3, // 'TECHNICAL
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('routes')->insert([
            'id' => 3,
            'code' => 'RUTA-03',
            'name' => 'RUTA-03',
            'state_id' => 1,
            'user_id' => 4, // 'TECHNICAL
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('routes')->insert([
            'id' => 4,
            'code' => 'RUTA-04',
            'name' => 'RUTA-04',
            'state_id' => 1,
            'user_id' => 5, // 'TECHNICAL
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('routes')->insert([
            'id' => 5,
            'code' => 'RUTA-05',
            'name' => 'RUTA-05',
            'state_id' => 1,
            'user_id' => 6, // 'TECHNICAL
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
