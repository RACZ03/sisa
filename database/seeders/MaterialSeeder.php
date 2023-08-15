<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materials')->insert([
            'code' => 'MAT001',
            'name' => 'OLT GPON',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT002',
            'name' => 'ONT GPON',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT003',
            'name' => 'Fibra Óptica',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT004',
            'name' => 'Splitters GPON',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT005',
            'name' => 'Caja Terminal GPON',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT006',
            'name' => 'Cable de Conexión GPON',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT007',
            'name' => 'Equipo de Prueba GPON',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT008',
            'name' => 'Cable de Alimentación GPON',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT009',
            'name' => 'Adaptador GPON',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT010',
            'name' => 'Módulo Óptico GPON',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);
    }
}
