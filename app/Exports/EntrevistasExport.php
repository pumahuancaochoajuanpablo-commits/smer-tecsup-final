<?php

namespace App\Exports;

use App\Models\Entrevista;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EntrevistasExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function query()
    {
        return Entrevista::with(['asignacion.estudiante.user', 'asignacion.tutor.user'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc');
    }

    public function map($entrevista): array
    {
        return [
            $entrevista->fecha?->format('d/m/Y') ?? 'N/A',
            $entrevista->asignacion?->estudiante?->user?->name ?? 'N/A',
            $entrevista->asignacion?->estudiante?->codigo ?? 'N/A',
            $entrevista->asignacion?->tutor?->user?->name ?? 'N/A',
            $entrevista->acad_2,
            $entrevista->emoc_2,
            $entrevista->soc_2,
            $entrevista->econ_2,
            $entrevista->fam_2,
            $entrevista->salud_2,
            $entrevista->puntaje_total,
            strtoupper((string) $entrevista->nivel_riesgo),
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Estudiante',
            'Codigo',
            'Tutor',
            'Academico',
            'Emocional',
            'Social',
            'Economico',
            'Familiar',
            'Salud',
            'Puntaje Total',
            'Nivel de Riesgo',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '366092']],
            ],
        ];
    }
}
