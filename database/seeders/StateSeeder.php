<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('states')->insert([
            'id' => 1,
            'code' => 'ACTIVE',
            'name' => 'Activo',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('states')->insert([
            'id' => 2,
            'code' => 'INACTIVE',
            'name' => 'Inactivo',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('states')->insert([
            'id' => 3,
            'code' => 'CANCELLED',
            'name' => 'Anulado',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // DB::table('states')->insert([
        //     'id' => 4,
        //     'code' => 'LOADPENDING',
        //     'name' => 'Carga Pendiente',
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);

        // DB::table('states')->insert([
        //     'id' => 5,
        //     'code' => 'LOADCOMPLETE',
        //     'name' => 'Carga Completa',
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);

        // DB::table('states')->insert([
        //     'id' => 6,
        //     'code' => 'LOADERROR',
        //     'name' => 'Carga con Errores',
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);

    }
}
