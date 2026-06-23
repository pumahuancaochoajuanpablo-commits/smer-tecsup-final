<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use Illuminate\Database\Seeder;

class EstudianteSeeder extends Seeder
{
    public function run(): void
    {
        $carreras = [
            'Diseno y Desarrollo de Software',
            'Administracion de Redes y Comunicaciones',
            'Big Data y Ciencia de Datos',
            'Marketing Digital Analitico',
            'Gestion de Seguridad y Salud en el Trabajo',
            'Mecatronica y Gestion Automotriz',
            'Electricidad Industrial',
            'Tecnologia Mecanica Electrica',
            'Produccion y Gestion Industrial',
            'Diseno Industrial',
        ];

        for ($userId = 5; $userId <= 24; $userId++) {
            Estudiante::create([
                'user_id' => $userId,
                'codigo' => 'EST' . str_pad((string) ($userId - 4), 3, '0', STR_PAD_LEFT),
                'carrera' => $carreras[($userId - 5) % count($carreras)],
                'ciclo' => (string) rand(1, 6),
                'grupo' => ['A', 'B', 'C', 'D'][($userId - 5) % 4],
                'edad' => rand(17, 24),
                'estado' => true,
            ]);
        }
    }
}
