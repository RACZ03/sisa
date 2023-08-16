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




        DB::table('users')->insert([
            'name' => 'DARWING GABRIEL HERNANDEZ GODINEZ',
            'email' => 'darwing@example.com',
            'password' => Hash::make('password123'), // You can use any desired password here
            'phone' => '12345678', // You can generate random phone numbers here if needed
            'role_id' => 3,
            'state_id' => 1, // 'Activo'
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'FRANKLIN CONCEPCION TORREZ BARRIOS',
            'email' => 'franclin@example.com',
            'password' => Hash::make('password123'), // You can use any desired password here
            'phone' => '12345678', // You can generate random phone numbers here if needed
            'role_id' => 3,
            'state_id' => 1, // 'Activo'
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'HENRY JOSE RIVAS',
            'email' => 'henry@example.com',
            'password' => Hash::make('password123'), // You can use any desired password here
            'phone' => '12345678', // You can generate random phone numbers here if needed
            'role_id' => 3,
            'state_id' => 1, // 'Activo'
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'MARELING KARINA GUTIERREZ',
            'email' => 'marling@example.com',
            'password' => Hash::make('password123'), // You can use any desired password here
            'phone' => '12345678', // You can generate random phone numbers here if needed
            'role_id' => 3,
            'state_id' => 1, // 'Activo'
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'MICHEL ANTONIO DELGADILLO MUÑOZ',
            'email' => 'michel@example.com',
            'password' => Hash::make('password123'), // You can use any desired password here
            'phone' => '12345678', // You can generate random phone numbers here if needed
            'role_id' => 3,
            'state_id' => 1, // 'Activo'
            'created_at' => now(),
            'updated_at' => now(),
        ]);


    }
}
