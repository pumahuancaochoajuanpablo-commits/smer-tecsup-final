<?php

namespace Database\Seeders;

use App\Models\ParametroRiesgo;
use Illuminate\Database\Seeder;

class ParametroRiesgoSeeder extends Seeder
{
    public function run(): void
    {
        ParametroRiesgo::insert([
            ['indicador' => 'acad_2', 'peso' => 20.00, 'umbral_bajo' => 3, 'umbral_medio' => 5, 'umbral_alto' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'emoc_2', 'peso' => 25.00, 'umbral_bajo' => 3, 'umbral_medio' => 5, 'umbral_alto' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'soc_2', 'peso' => 15.00, 'umbral_bajo' => 3, 'umbral_medio' => 5, 'umbral_alto' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'econ_2', 'peso' => 15.00, 'umbral_bajo' => 3, 'umbral_medio' => 5, 'umbral_alto' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'fam_2', 'peso' => 15.00, 'umbral_bajo' => 3, 'umbral_medio' => 5, 'umbral_alto' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['indicador' => 'salud_2', 'peso' => 10.00, 'umbral_bajo' => 3, 'umbral_medio' => 5, 'umbral_alto' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
