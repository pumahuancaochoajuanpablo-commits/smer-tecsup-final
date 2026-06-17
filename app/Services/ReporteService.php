<?php

namespace App\Services;

use App\Models\Entrevista;
use App\Models\Estudiante;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteService
{
    /**
     * Generar PDF de ficha individual de estudiante
     */
    public function fichaIndividualPDF(Estudiante $estudiante)
    {
        $asignacion = $estudiante->asignaciones()->first();
        $entrevistas = Entrevista::where('asignacion_id', $asignacion?->id)
            ->orderBy('fecha', 'desc')
            ->get();

        $data = [
            'estudiante' => $estudiante->load('user'),
            'asignacion' => $asignacion,
            'entrevistas' => $entrevistas,
            'resumen' => $this->generarResumen($entrevistas),
        ];

        $pdf = Pdf::loadView('reportes.ficha-individual', $data);
        return $pdf->download("ficha_{$estudiante->codigo}.pdf");
    }

    /**
     * Generar PDF de informe general
     */
    public function informeGeneralPDF()
    {
        $entrevistas = Entrevista::with(['asignacion.estudiante.user', 'asignacion.tutor.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $riesgos = [
            'alto' => $entrevistas->where('nivel_riesgo', 'alto')->count(),
            'medio' => $entrevistas->where('nivel_riesgo', 'medio')->count(),
            'bajo' => $entrevistas->where('nivel_riesgo', 'bajo')->count(),
        ];

        $data = [
            'entrevistas' => $entrevistas,
            'riesgos' => $riesgos,
            'total' => $entrevistas->count(),
            'fecha_generacion' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('reportes.informe-general', $data);
        return $pdf->download("informe_general_" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Generar Excel de entrevistas
     */
    public function exportarEntrevistasExcel()
    {
        return \Excel::download(new \App\Exports\EntrevistasExport, 'entrevistas_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Generar resumen estadístico de entrevistas
     */
    private function generarResumen($entrevistas)
    {
        $total = $entrevistas->count();
        
        if ($total === 0) {
            return [
                'total' => 0,
                'promedio_puntaje' => 0,
                'riesgo_predominante' => 'N/A',
                'tendencia' => 'Sin datos',
            ];
        }

        $promedio = $entrevistas->avg('puntaje_total');
        $riesgos = $entrevistas->groupBy('nivel_riesgo')->map->count();
        $predominante = $riesgos->sortDesc()->keys()->first();

        // Determinar tendencia (últimas 3 entrevistas)
        $ultimas = $entrevistas->take(3);
        $tendencia = $this->determinarTendencia($ultimas);

        return [
            'total' => $total,
            'promedio_puntaje' => round($promedio, 2),
            'riesgo_predominante' => strtoupper($predominante),
            'tendencia' => $tendencia,
            'distribucion' => $riesgos->toArray(),
        ];
    }

    /**
     * Determinar si el riesgo mejora o empeora
     */
    private function determinarTendencia($ultimas)
    {
        if ($ultimas->count() < 2) {
            return 'Datos insuficientes';
        }

        $nivelRiesgo = [
            'alto' => 3,
            'medio' => 2,
            'bajo' => 1,
        ];

        $valores = $ultimas->pluck('nivel_riesgo')->map(fn($r) => $nivelRiesgo[$r])->toArray();
        
        $diferencia = end($valores) - reset($valores);

        if ($diferencia > 0) {
            return '✅ Mejorando';
        } elseif ($diferencia < 0) {
            return '⚠️ Empeorando';
        } else {
            return '➡️ Estable';
        }
    }
}
