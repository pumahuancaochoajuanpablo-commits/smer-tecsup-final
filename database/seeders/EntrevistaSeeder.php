<?php

namespace Database\Seeders;

use App\Models\Asignacion;
use App\Models\Entrevista;
use Illuminate\Database\Seeder;

class EntrevistaSeeder extends Seeder
{
    public function run(): void
    {
        Asignacion::query()->each(function (Asignacion $asignacion) {
            foreach (range(1, rand(1, 3)) as $index) {
                $puntajes = [
                    'acad_2' => rand(1, 3),
                    'emoc_2' => rand(1, 3),
                    'soc_2' => rand(1, 3),
                    'econ_2' => rand(1, 3),
                    'fam_2' => rand(1, 3),
                    'salud_2' => rand(1, 3),
                ];

                $total = array_sum($puntajes);

                Entrevista::create([
                    'asignacion_id' => $asignacion->id,
                    'fecha' => now()->subDays(rand(1, 90))->format('Y-m-d'),
                    ...$puntajes,
                    'puntaje_total' => $total,
                    'nivel_riesgo' => match (true) {
                        $total >= 14 => 'alto',
                        $total >= 8 => 'medio',
                        default => 'bajo',
                    },
                ]);
            }
        });
    }
}
