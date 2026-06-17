<?php

namespace App\Exports;

use App\Models\Entrevista;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EntrevistasExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Entrevista::with(['asignacion.estudiante.user', 'asignacion.tutor.user'])
            ->get()
            ->map(function ($entrevista) {
                return [
                    'Fecha' => $entrevista->fecha->format('d/m/Y'),
                    'Estudiante' => $entrevista->asignacion->estudiante->user->name ?? 'N/A',
                    'Código' => $entrevista->asignacion->estudiante->codigo ?? 'N/A',
                    'Tutor' => $entrevista->asignacion->tutor->user->name ?? 'N/A',
                    'Académico' => $entrevista->acad_2,
                    'Emocional' => $entrevista->emoc_2,
                    'Social' => $entrevista->soc_2,
                    'Económico' => $entrevista->econ_2,
                    'Familiar' => $entrevista->fam_2,
                    'Salud' => $entrevista->salud_2,
                    'Puntaje Total' => $entrevista->puntaje_total,
                    'Nivel de Riesgo' => strtoupper($entrevista->nivel_riesgo),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Estudiante',
            'Código',
            'Tutor',
            'Académico',
            'Emocional',
            'Social',
            'Económico',
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
