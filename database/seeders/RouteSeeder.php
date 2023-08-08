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
        $role = Role::where('code', 'TECHNICAL')->first();

        // get first user with role TECHNICAL
        $user = User::where('role_id', $role->id)->first();

        DB::table('routes')->insert([
            'id' => 1,
            'code' => 'RUTA-001',
            'name' => 'RUTA-001',
            'state_id' => 1,
            'user_id' => $user->id, // 'TECHNICAL
            'description' => 'RUTA-001',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $user2 = User::where('role_id', $role->id)->skip(1)->first();

        DB::table('routes')->insert([
            'id' => 2,
            'code' => 'RUTA-002',
            'name' => 'RUTA-002',
            'state_id' => 1,
            'user_id' => $user2->id, // 'TECHNICAL
            'description' => 'RUTA-002',
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
