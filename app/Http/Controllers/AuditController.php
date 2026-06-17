<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * CUS10: Mostrar logs de auditoría
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filtrar por acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        // Filtrar por modelo
        if ($request->filled('modelo')) {
            $query->where('modelo', $request->modelo);
        }

        // Filtrar por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtrar por rango de fechas
        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        $logs = $query->paginate(50);
        $acciones = AuditLog::distinct('accion')->pluck('accion');
        $modelos = AuditLog::distinct('modelo')->pluck('modelo');

        return view('admin.auditoria.index', compact('logs', 'acciones', 'modelos'));
    }

    /**
     * Mostrar detalles de un log
     */
    public function show(AuditLog $log)
    {
        return view('admin.auditoria.show', compact('log'));
    }

    /**
     * Exportar logs a Excel
     */
    public function exportarExcel()
    {
        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return \Excel::download(new \App\Exports\AuditLogsExport($logs), 'auditoria_' . now()->format('Y-m-d') . '.xlsx');
    }
}
