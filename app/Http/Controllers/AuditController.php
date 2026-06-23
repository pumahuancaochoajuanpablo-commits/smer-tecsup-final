<?php

namespace App\Http\Controllers;

use App\Exports\AuditLogsExport;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AuditController extends Controller
{
    /**
     * CUS10: Mostrar logs de auditoria.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('modelo')) {
            $query->where('modelo', $request->modelo);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

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

    public function show(AuditLog $log)
    {
        return view('admin.auditoria.show', compact('log'));
    }

    public function exportarExcel(Request $request)
    {
        $filters = $request->only(['accion', 'modelo', 'user_id', 'desde', 'hasta']);

        return Excel::download(new AuditLogsExport($filters), 'auditoria_' . now()->format('Y-m-d') . '.xlsx');
    }
}
