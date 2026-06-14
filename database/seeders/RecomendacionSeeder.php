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
                'acciones' => 'Derivar a consejería estudiantil. Monitoreo quincenal. Citar a reunión con padres de familia.',
            ],
            [
                'nivel_riesgo' => 'alto',
                'acciones' => 'Derivación psicológica inmediata. Notificar a Bienestar Universitario. Activación de protocolo de intervención.',
            ],
        ]);
    }
}
