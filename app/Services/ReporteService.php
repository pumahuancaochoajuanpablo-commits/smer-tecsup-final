<?php

namespace App\Services;

use App\Exports\EntrevistasExport;
use App\Models\Entrevista;
use App\Models\Estudiante;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class ReporteService
{
    public function fichaIndividualPDF(Estudiante $estudiante)
    {
        $data = $this->datosFichaIndividual($estudiante);

        $pdf = Pdf::loadView('reportes.ficha-individual', $data);

        return $pdf->download("ficha_{$estudiante->codigo}.pdf");
    }

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

        return $pdf->download('informe_general_' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportarEntrevistasExcel()
    {
        return Excel::download(new EntrevistasExport(), 'entrevistas_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportarFichasMasivasZip()
    {
        abort_unless(extension_loaded('zip'), 500, 'La extension ZIP de PHP no esta habilitada.');

        $folder = 'exports';
        Storage::disk('local')->makeDirectory($folder);

        $fileName = 'fichas_estudiantes_' . now()->format('Y-m-d_His') . '.zip';
        $relativePath = "{$folder}/{$fileName}";
        $absolutePath = Storage::disk('local')->path($relativePath);

        $zip = new ZipArchive();
        abort_if($zip->open($absolutePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true, 500, 'No se pudo crear el archivo ZIP.');

        Estudiante::with('user')
            ->orderBy('codigo')
            ->chunk(50, function ($estudiantes) use ($zip) {
                foreach ($estudiantes as $estudiante) {
                    $data = $this->datosFichaIndividual($estudiante);
                    $pdf = Pdf::loadView('reportes.ficha-individual', $data);
                    $codigo = preg_replace('/[^A-Za-z0-9_-]/', '_', (string) $estudiante->codigo);

                    $zip->addFromString("fichas/ficha_{$codigo}.pdf", $pdf->output());
                }
            });

        $zip->close();

        return response()->download($absolutePath)->deleteFileAfterSend(true);
    }

    private function datosFichaIndividual(Estudiante $estudiante): array
    {
        $estudiante->loadMissing('user');
        $asignacion = $estudiante->asignaciones()->with('tutor.user')->first();
        $entrevistas = Entrevista::where('asignacion_id', $asignacion?->id)
            ->orderBy('fecha', 'desc')
            ->get();

        return [
            'estudiante' => $estudiante,
            'asignacion' => $asignacion,
            'entrevistas' => $entrevistas,
            'resumen' => $this->generarResumen($entrevistas),
        ];
    }

    private function generarResumen($entrevistas): array
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
        $ultimas = $entrevistas->take(3);

        return [
            'total' => $total,
            'promedio_puntaje' => round($promedio, 2),
            'riesgo_predominante' => strtoupper((string) $predominante),
            'tendencia' => $this->determinarTendencia($ultimas),
            'distribucion' => $riesgos->toArray(),
        ];
    }

    private function determinarTendencia($ultimas): string
    {
        if ($ultimas->count() < 2) {
            return 'Datos insuficientes';
        }

        $nivelRiesgo = [
            'alto' => 3,
            'medio' => 2,
            'bajo' => 1,
        ];

        $valores = $ultimas
            ->pluck('nivel_riesgo')
            ->map(fn ($riesgo) => $nivelRiesgo[$riesgo] ?? 0)
            ->toArray();

        $diferencia = end($valores) - reset($valores);

        return match (true) {
            $diferencia > 0 => 'Mejorando',
            $diferencia < 0 => 'Empeorando',
            default => 'Estable',
        };
    }
}
