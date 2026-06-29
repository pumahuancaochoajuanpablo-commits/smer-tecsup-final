<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditLogsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private array $filters = [])
    {
    }

    public function query()
    {
        return AuditLog::with('user')
            ->when($this->filters['accion'] ?? null, fn ($query, $accion) => $query->where('accion', $accion))
            ->when($this->filters['modelo'] ?? null, fn ($query, $modelo) => $query->where('modelo', $modelo))
            ->when($this->filters['user_id'] ?? null, fn ($query, $userId) => $query->where('user_id', $userId))
            ->when($this->filters['desde'] ?? null, fn ($query, $desde) => $query->whereDate('created_at', '>=', $desde))
            ->when($this->filters['hasta'] ?? null, fn ($query, $hasta) => $query->whereDate('created_at', '<=', $hasta))
            ->orderBy('created_at', 'desc');
    }

    public function map($log): array
    {
        return [
            $log->created_at?->format('d/m/Y H:i:s') ?? 'N/A',
            $log->user?->name ?? 'Sistema',
            $this->accionLegible($log->accion),
            $log->modelo,
            $log->modelo_id,
            $log->ip_address,
            $this->detallesLegibles($log->detalles ?? []),
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Usuario',
            'Accion',
            'Modelo',
            'Registro',
            'Direccion IP',
            'Detalles',
        ];
    }

    private function accionLegible(?string $accion): string
    {
        return [
            'create' => 'Creado',
            'update' => 'Actualizado',
            'delete' => 'Eliminado',
        ][$accion] ?? ucfirst((string) $accion);
    }

    private function detallesLegibles(array $detalles): string
    {
        $campos = [
            'asignacion_id' => 'Asignacion',
            'estudiante' => 'Estudiante',
            'puntaje' => 'Puntaje obtenido',
            'nivel_riesgo' => 'Nivel de riesgo',
            'info' => 'Informacion',
        ];

        return collect($detalles)
            ->map(function ($valor, $campo) use ($campos) {
                $etiqueta = $campos[$campo] ?? str_replace('_', ' ', ucfirst((string) $campo));
                $contenido = is_array($valor) ? implode(', ', $valor) : $valor;

                if (is_string($contenido)) {
                    $contenido = ucfirst($contenido);
                }

                return $etiqueta . ': ' . $contenido;
            })
            ->implode(' | ');
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
