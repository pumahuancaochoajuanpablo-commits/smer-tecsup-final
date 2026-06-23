<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Derivacion;
use App\Models\Estudiante;
use App\Models\User;
use App\Services\BrevoEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DerivacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:tutor,admin');
    }

    public function index()
    {
        $derivaciones = Derivacion::with(['estudiante.user', 'tutor.user'])
            ->latest('fecha_derivacion')
            ->paginate(15);

        return view('derivaciones.index', compact('derivaciones'));
    }

    public function crear($estudianteId)
    {
        $estudiante = Estudiante::with('user')->findOrFail($estudianteId);
        $tutor = Auth::user()->tutor;

        if (! $tutor) {
            return redirect()->back()->with('error', 'No tienes permisos para derivar estudiantes.');
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

            if (! $tutor) {
                return redirect()->back()->with('error', 'No tienes permisos para registrar derivaciones.');
            }

            $derivacion = Derivacion::create([
                'estudiante_id' => $validated['estudiante_id'],
                'tutor_id' => $tutor->id,
                'motivo' => $validated['motivo'],
                'descripcion' => $validated['descripcion'],
                'responsable_bienestar' => $validated['responsable_bienestar'],
                'estado' => 'pendiente',
                'observaciones' => [],
            ]);

            $this->notificarAdmin($derivacion);

            AuditLog::registrar('create', 'Derivacion', $derivacion->id, [
                'estudiante_id' => $derivacion->estudiante_id,
                'motivo' => $derivacion->motivo,
            ]);

            return redirect()->route('derivaciones.index')
                ->with('success', 'Derivacion registrada correctamente. Se aviso al administrador de Bienestar.');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar derivacion: '.$exception->getMessage());
        }
    }

    public function ver($id)
    {
        $derivacion = Derivacion::with(['estudiante.user', 'tutor.user'])->findOrFail($id);

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

            if ($request->filled('observacion')) {
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
                ->with('success', 'Derivacion actualizada correctamente.');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->with('error', 'Error al actualizar derivacion: '.$exception->getMessage());
        }
    }

    public function exportarExcel()
    {
        $derivaciones = Derivacion::with(['estudiante.user', 'tutor.user'])->get();

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

    private function notificarAdmin(Derivacion $derivacion): void
    {
        $admin = User::where('email', 'yeferson.quispe@tecsup.edu.pe')->first();

        if (! $admin) {
            Log::warning('No se encontro el administrador de Bienestar para notificar derivacion.', [
                'derivacion_id' => $derivacion->id,
            ]);
            return;
        }

        $derivacion->loadMissing(['estudiante.user', 'tutor.user']);

        try {
            app(BrevoEmailService::class)->send(
                $admin->email,
                $admin->name,
                'Nueva derivacion de riesgo alto - SMER Tecsup',
                view('emails.derivacion-alerta', [
                    'estudianteNombre' => $derivacion->estudiante->user->name,
                    'estudianteCodigo' => $derivacion->estudiante->codigo,
                    'tutorNombre' => $derivacion->tutor->user->name,
                    'motivo' => $derivacion->motivo,
                    'descripcion' => $derivacion->descripcion,
                ])->render(),
                "Nueva derivacion: {$derivacion->estudiante->user->name}. Motivo: {$derivacion->motivo}. Se recomienda derivacion a psicologia o bienestar estudiantil."
            );
        } catch (\Throwable $exception) {
            Log::error('No se pudo notificar al administrador sobre la derivacion.', [
                'derivacion_id' => $derivacion->id,
                'admin_email' => $admin->email,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
