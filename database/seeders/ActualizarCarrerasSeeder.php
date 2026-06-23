<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use Illuminate\Database\Seeder;

class ActualizarCarrerasSeeder extends Seeder
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

        Estudiante::query()
            ->orderBy('id')
            ->get()
            ->each(function (Estudiante $estudiante, int $index) use ($carreras) {
                $estudiante->update([
                    'carrera' => $carreras[$index % count($carreras)],
                ]);
            });
    }
}
