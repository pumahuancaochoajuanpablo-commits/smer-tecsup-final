<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditLogsExport implements FromCollection, WithHeadings, WithStyles
{
    private $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function collection()
    {
        return $this->logs->map(function ($log) {
            return [
                'Fecha' => $log->created_at->format('d/m/Y H:i:s'),
                'Usuario' => $log->user?->name ?? 'Sistema',
                'Acción' => strtoupper($log->accion),
                'Modelo' => $log->modelo,
                'ID Registro' => $log->modelo_id,
                'Dirección IP' => $log->ip_address,
                'Detalles' => json_encode($log->detalles ?? []),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Usuario',
            'Acción',
            'Modelo',
            'ID Registro',
            'Dirección IP',
            'Detalles',
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
