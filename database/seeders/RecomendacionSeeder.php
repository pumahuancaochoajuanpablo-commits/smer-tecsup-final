<?php

namespace Database\Seeders;

use App\Models\Recomendacion;
use Illuminate\Database\Seeder;

class RecomendacionSeeder extends Seeder
{
    public function run(): void
    {
        Recomendacion::insert([
            [
                'nivel_riesgo' => 'bajo',
                'acciones' => 'Seguimiento regular por parte del tutor. Monitoreo mensual de indicadores.',
            ],
            [
                'nivel_riesgo' => 'medio',
                'acciones' => 'Derivar a consejeria estudiantil. Monitoreo quincenal. Citar a reunion con padres de familia.',
            ],
            [
                'nivel_riesgo' => 'alto',
                'acciones' => 'Derivacion psicologica inmediata. Notificar a Bienestar Estudiantil. Activacion de protocolo de intervencion.',
            ],
        ]);
    }
}
