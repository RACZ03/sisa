<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@sisa.com',
            'password' => Hash::make('secret'),
            'phone' => '86604980',
            'role_id' => 1, // Super Administrador
            'state_id' => 1, // 'Activo'
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $roles = [2, 2, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4]; // Role_id values for 15 users

        for ($i = 1; $i <= 15; $i++) {
            DB::table('users')->insert([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password123'), // You can use any desired password here
                'phone' => '12345678'.$i, // You can generate random phone numbers here if needed
                'role_id' => $roles[$i - 1], // Get the corresponding role_id from the roles array
                'state_id' => 1, // 'Activo'
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


    }
}
