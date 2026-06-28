<?php

namespace Database\Seeders;

use App\Models\Asignacion;
use Illuminate\Database\Seeder;

class AsignacionSeeder extends Seeder
{
    public function run(): void
    {

        $asignaciones = [];
        $estId = 5;
        for ($tutorId = 1; $tutorId <= 3; $tutorId++) {
            $limite = ($tutorId === 2) ? 6 : 7;
            for ($j = 0; $j < $limite; $j++) {
                $asignaciones[] = [
                    'tutor_id' => $tutorId,
                    'estudiante_id' => $estId - 4,
                    'fecha_inicio' => now()->subDays(rand(30, 90))->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $estId++;
            }
        }

        Asignacion::insert($asignaciones);
    }
}
