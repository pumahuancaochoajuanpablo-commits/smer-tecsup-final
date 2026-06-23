<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Asignacion;
use App\Models\Derivacion;
use App\Models\Estudiante;
use App\Models\Role;
use App\Models\User;
use App\Services\BrevoEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DerivacionController extends Controller
{
    public function index()
    {
        $derivaciones = Derivacion::with(['estudiante.user', 'tutor.user'])
            ->latest('fecha_derivacion')
            ->paginate(15);

        return view('derivaciones.index', compact('derivaciones'));
    }

    public function crear($estudianteId)
    {
        try {
            $estudiante = Estudiante::with('user')->findOrFail($estudianteId);
            $tutor = Auth::user()->tutor;

            if (! $tutor) {
                return redirect()->back()->with('error', 'No tienes permisos para derivar estudiantes.');
            }

            return view('derivaciones.crear', compact('estudiante', 'tutor'));
        } catch (\Throwable $exception) {
            Log::error('No se pudo abrir el formulario de derivacion.', [
                'estudiante_id' => $estudianteId,
                'user_id' => Auth::id(),
                'error' => $exception->getMessage(),
            ]);

            return redirect()->route('tutor.alertas')
                ->with('error', 'No se pudo abrir la derivacion. Verifica que el estudiante exista y este asignado al tutor.');
        }
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

            $tieneAsignacion = Asignacion::where('tutor_id', $tutor->id)
                ->where('estudiante_id', $validated['estudiante_id'])
                ->exists();

            if (! $tieneAsignacion) {
                return redirect()->back()
                    ->with('error', 'No puedes derivar a un estudiante que no tienes asignado.');
            }

            $derivacionExistente = Derivacion::where('estudiante_id', $validated['estudiante_id'])
                ->where('tutor_id', $tutor->id)
                ->whereIn('estado', ['pendiente', 'derivado'])
                ->latest('fecha_derivacion')
                ->first();

            if ($derivacionExistente) {
                return redirect()->route('tutor.alertas')
                    ->with('success', 'Este estudiante ya tiene una derivacion activa. Puedes revisar el seguimiento en Alertas.');
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

            $correoEnviado = $this->notificarAdmin($derivacion);

            AuditLog::registrar('create', 'Derivacion', $derivacion->id, [
                'estudiante_id' => $derivacion->estudiante_id,
                'motivo' => $derivacion->motivo,
            ]);

            $mensaje = $correoEnviado
                ? 'Derivacion registrada correctamente. Se aviso al administrador de Bienestar.'
                : 'Derivacion registrada correctamente, pero no se pudo enviar el correo al administrador. Revisa BREVO_API_KEY y el remitente verificado.';

            return redirect()->route('tutor.alertas')
                ->with($correoEnviado ? 'success' : 'error', $mensaje);
        } catch (\Throwable $exception) {
            Log::error('No se pudo registrar la derivacion.', [
                'user_id' => Auth::id(),
                'payload' => $request->except(['_token']),
                'error' => $exception->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo registrar la derivacion. Motivo tecnico: '.$exception->getMessage());
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

    private function notificarAdmin(Derivacion $derivacion): bool
    {
        $admin = $this->adminBienestar();

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

            return true;
        } catch (\Throwable $exception) {
            Log::error('No se pudo notificar al administrador sobre la derivacion.', [
                'derivacion_id' => $derivacion->id,
                'admin_email' => $admin->email,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function adminBienestar(): User
    {
        $adminRole = Role::firstOrCreate(['nombre' => 'admin']);

        return User::updateOrCreate(
            ['email' => 'yeferson.quispe@tecsup.edu.pe'],
            [
                'name' => 'Yeferson Quispe',
                'password' => Hash::make('admin123'),
                'rol_id' => $adminRole->id,
                'estado' => true,
            ]
        );
    }
}
