<?php

namespace Database\Seeders;

use App\Models\Asignacion;
use Illuminate\Database\Seeder;

class AsignacionSeeder extends Seeder
{
    public function run(): void
    {
        // Tutor 1 (user_id 2) -> estudiantes 5-11 (7 estudiantes)
        // Tutor 2 (user_id 3) -> estudiantes 12-17 (6 estudiantes)
        // Tutor 3 (user_id 4) -> estudiantes 18-24 (7 estudiantes)

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
