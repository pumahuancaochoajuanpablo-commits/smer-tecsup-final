<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use Illuminate\Database\Seeder;

class EstudianteSeeder extends Seeder
{
    public function run(): void
    {
        $carreras = ['Ingeniería de Sistemas', 'Psicología', 'Administración', 'Medicina', 'Derecho', 'Arquitectura', 'Contabilidad', 'Enfermería'];

        $estudiantes = [];
        for ($i = 5; $i <= 24; $i++) {
            $userId = $i;
            $codigo = 'EST' . str_pad($i - 4, 3, '0', STR_PAD_LEFT);
            $carrera = $carreras[array_rand($carreras)];
            $estudiantes[] = [
                'user_id' => $userId,
                'codigo' => $codigo,
                'carrera' => $carrera,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Estudiante::insert($estudiantes);
    }
}
