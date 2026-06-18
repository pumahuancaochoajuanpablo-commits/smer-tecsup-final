<?php

namespace App\Http\Controllers;

use App\Models\Derivacion;
use App\Models\Estudiante;
use App\Models\Tutor;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DerivacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:tutor,admin');
    }

    public function index()
    {
        $derivaciones = Derivacion::with(['estudiante', 'tutor'])
            ->latest('fecha_derivacion')
            ->paginate(15);
        
        return view('derivaciones.index', compact('derivaciones'));
    }

    public function crear($estudianteId)
    {
        $estudiante = Estudiante::findOrFail($estudianteId);
        $tutor = Auth::user()->tutor;
        
        if (!$tutor) {
            return redirect()->back()->with('error', 'No tiene permisos para derivar estudiantes');
        }
        
        return view('derivaciones.crear', compact('estudiante', 'tutor'));
    }

    public function registrar(Request $request)
    {
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'motivo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'responsable_bienestar' => 'nullable|string|max:255',
        ]);

        try {
            $tutor = Auth::user()->tutor;
            if (!$tutor) {
                return redirect()->back()->with('error', 'No tiene permisos para registrar derivaciones');
            }

            $derivacion = Derivacion::create([
                'estudiante_id' => $validated['estudiante_id'],
                'tutor_id' => $tutor->id,
                'motivo' => $validated['motivo'],
                'descripcion' => $validated['descripcion'],
                'responsable_bienestar' => $validated['responsable_bienestar'],
                'estado' => 'pendiente',
                'observaciones' => json_encode([]),
            ]);

            AuditLog::registrar('create', 'Derivacion', $derivacion->id, [
                'estudiante_id' => $derivacion->estudiante_id,
                'motivo' => $derivacion->motivo,
            ]);

            return redirect()->route('derivaciones.index')
                ->with('success', 'Derivación registrada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar derivación: ' . $e->getMessage());
        }
    }

    public function ver($id)
    {
        $derivacion = Derivacion::with(['estudiante', 'tutor'])->findOrFail($id);
        
        return view('derivaciones.ver', compact('derivacion'));
    }

    public function actualizar(Request $request, $id)
    {
        $derivacion = Derivacion::findOrFail($id);
        
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,derivado,rechazado,completado',
            'responsable_bienestar' => 'nullable|string|max:255',
            'observacion' => 'nullable|string|max:500',
        ]);

        try {
            $estadoAnterior = $derivacion->estado;
            
            $derivacion->update([
                'estado' => $validated['estado'],
                'responsable_bienestar' => $validated['responsable_bienestar'] ?? $derivacion->responsable_bienestar,
            ]);

            if ($validated['estado'] !== 'pendiente') {
                $derivacion->fecha_respuesta = now();
                $derivacion->save();
            }

            // Agregar observación al JSON
            if ($request->has('observacion') && $validated['observacion']) {
                $observaciones = $derivacion->observaciones ?? [];
                $observaciones[] = [
                    'fecha' => now()->toDateTimeString(),
                    'usuario' => Auth::user()->name,
                    'comentario' => $validated['observacion'],
                ];
                $derivacion->update(['observaciones' => $observaciones]);
            }

            AuditLog::registrar('update', 'Derivacion', $derivacion->id, [
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $validated['estado'],
            ]);

            return redirect()->route('derivaciones.ver', $id)
                ->with('success', 'Derivación actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar derivación: ' . $e->getMessage());
        }
    }

    public function exportarExcel()
    {
        $derivaciones = Derivacion::with(['estudiante', 'tutor'])->get();
        
        return view('derivaciones.excel', compact('derivaciones'));
    }

    public function estadisticas()
    {
        $stats = [
            'total' => Derivacion::count(),
            'pendientes' => Derivacion::where('estado', 'pendiente')->count(),
            'derivadas' => Derivacion::where('estado', 'derivado')->count(),
            'completadas' => Derivacion::where('estado', 'completado')->count(),
            'rechazadas' => Derivacion::where('estado', 'rechazado')->count(),
        ];
        
        return view('derivaciones.estadisticas', compact('stats'));
    }
}

