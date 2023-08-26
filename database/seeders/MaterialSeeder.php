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
        // HFC
        DB::table('materials')->insert([
            'code' => '1000819',
            'name' => 'SPLITER 4 VIAS RG-6 /CATV',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1000820',
            'name' => 'SPLITER 2 VIAS RG-6 /CATV',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1000822',
            'name' => 'SPLITER 3 VIAS NO BALANCEADO RG-6 /CATV',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1000859',
            'name' => 'SEÑALIZADOR ROJO',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1000888',
            'name' => 'SEÑALIZADOR AZUL',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1000902',
            'name' => 'PROTECTOR CONTRA PICOS HOLLAND CSI-CPE',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1000917',
            'name' => 'SEÑALIZADOR VERDE',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1000942',
            'name' => 'CONECTOR PPC',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1000953',
            'name' => 'CABLE RG6 TRI-SHIELD MENSAJERO 801 355AM',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1001579',
            'name' => 'PROTECTOR PARA CONECTOR R',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1006215',
            'name' => 'BRIDA PLASTIC CABLE COAXIL RG-6',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1020477',
            'name' => 'PROTECTOR CONTRA PICOS HOLLAND CPI-WHP',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '4010589',
            'name' => 'EMTA KAON CG 2200',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '4010599',
            'name' => 'DCT 700 REFURBISHED',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '4013936',
            'name' => 'STB DCX 3210 SAGENT',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '7003300',
            'name' => 'CONTROL REMOTO SMK ULA RRC9002-4880F',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 1,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);


        // GPON

        DB::table('materials')->insert([
            'code' => '1021568',
            'name' => 'HUEAWEI DESBAL CABLE AEREODUCTO DROP 050 M',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1021569',
            'name' => 'HUEAWEI DESBAL CABLE AEREODUCTO DROP 100 M',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1021570',
            'name' => 'HUEAWEI DESBAL CABLE AEREODUCTO DROP 150 M',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1020904',
            'name' => 'HUAWEI ROSETA OPTICA ATB3101',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1023519',
            'name' => 'HERRAJE TENCLAMP S DROP F8 ICT3103-A1',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '4013896',
            'name' => 'ONT GPON HUAWEI HG8245W5-6T',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '4013099',
            'name' => 'OTT PLAYER ZTE ZXV10 866v2',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '7005296',
            'name' => 'CONTROL UNIVERSAL ECOSS IPTV AN4804-OTT',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1004099',
            'name' => 'CABLE UTP CATEGORIA 6 664466CM',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '4009764',
            'name' => 'RJ45, CAT 6 PANDUIT',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '1020912',
            'name' => 'HUAWEI CONECTOR MECÁNICO FMC2104-SA',
            'stock' => 0,
            'has_series' => false,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);

        DB::table('materials')->insert([
            'code' => '4014467',
            'name' => 'SWITCH DLINK DGS108',
            'stock' => 0,
            'has_series' => true,
            'technology_id' => 2,
            'state_id' => 1, // 'ACTIVE'
            'created_at' => now()
        ]);
    }
}
