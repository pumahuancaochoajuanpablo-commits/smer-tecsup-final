<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use Illuminate\Database\Seeder;

class ActualizarCarrerasSeeder extends Seeder
{
    /**
     * Actualiza la carrera de los estudiantes YA EXISTENTES en la base de datos,
     * reemplazando los nombres genéricos/universitarios anteriores por
     * carreras reales de Tecsup. No borra ni toca entrevistas, derivaciones
     * ni ningún otro dato — solo actualiza la columna 'carrera'.
     */
    public function run(): void
    {
        $carreras = [
            'Diseño y Desarrollo de Software',
            'Administración de Redes y Comunicaciones',
            'Big Data y Ciencia de Datos',
            'Marketing Digital Analítico',
            'Gestión de Seguridad y Salud en el Trabajo',
            'Mecatrónica y Gestión Automotriz',
            'Electricidad Industrial con mención en Sistemas Eléctricos de Potencia',
            'Tecnología Mecánica Eléctrica',
            'Producción y Gestión Industrial',
            'Diseño Industrial',
            'Modelado y Animación Digital',
            'Administración y Emprendimiento en Negocios Digitales',
        ];

        $estudiantes = Estudiante::all();

        foreach ($estudiantes as $estudiante) {
            $estudiante->update([
                'carrera' => $carreras[array_rand($carreras)],
            ]);
        }

        $this->command->info($estudiantes->count() . ' estudiantes actualizados con carreras reales de Tecsup.');
    }
}