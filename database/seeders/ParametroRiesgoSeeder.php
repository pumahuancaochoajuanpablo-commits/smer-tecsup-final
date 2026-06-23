<?php

namespace Database\Seeders;

use App\Models\ParametroRiesgo;
use Illuminate\Database\Seeder;

class ParametroRiesgoSeeder extends Seeder
{
    public function run(): void
    {
        ParametroRiesgo::insert([
            ['indicador' => 'acad_2', 'peso' => 1.00, 'umbral_bajo' => 7, 'umbral_medio' => 8, 'umbral_alto' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'emoc_2', 'peso' => 1.00, 'umbral_bajo' => 7, 'umbral_medio' => 8, 'umbral_alto' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'soc_2', 'peso' => 1.00, 'umbral_bajo' => 7, 'umbral_medio' => 8, 'umbral_alto' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'econ_2', 'peso' => 1.00, 'umbral_bajo' => 7, 'umbral_medio' => 8, 'umbral_alto' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'fam_2', 'peso' => 1.00, 'umbral_bajo' => 7, 'umbral_medio' => 8, 'umbral_alto' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'salud_2', 'peso' => 1.00, 'umbral_bajo' => 7, 'umbral_medio' => 8, 'umbral_alto' => 14, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
