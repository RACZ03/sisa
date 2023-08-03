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
            'description' => 'OLT (Optical Line Terminal) para tecnología GPON',
            'stock' => 50,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT002',
            'name' => 'ONT GPON',
            'description' => 'ONT (Optical Network Terminal) para tecnología GPON',
            'stock' => 100,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT003',
            'name' => 'Fibra Óptica',
            'description' => 'Cable de fibra óptica monomodo para GPON',
            'stock' => 5000,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT004',
            'name' => 'Splitters GPON',
            'description' => 'Splitters para tecnología GPON',
            'stock' => 200,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT005',
            'name' => 'Caja Terminal GPON',
            'description' => 'Caja terminal para tecnología GPON',
            'stock' => 30,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT006',
            'name' => 'Cable de Conexión GPON',
            'description' => 'Cable de conexión para tecnología GPON',
            'stock' => 1000,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT007',
            'name' => 'Equipo de Prueba GPON',
            'description' => 'Equipo de prueba para tecnología GPON',
            'stock' => 10,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT008',
            'name' => 'Cable de Alimentación GPON',
            'description' => 'Cable de alimentación para tecnología GPON',
            'stock' => 500,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT009',
            'name' => 'Adaptador GPON',
            'description' => 'Adaptador para tecnología GPON',
            'stock' => 50,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => 'MAT010',
            'name' => 'Módulo Óptico GPON',
            'description' => 'Módulo óptico para tecnología GPON',
            'stock' => 100,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);
    }
}
