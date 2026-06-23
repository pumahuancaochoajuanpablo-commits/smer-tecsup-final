<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\AuditLog;
use App\Models\Derivacion;
use App\Models\Entrevista;
use App\Models\Estudiante;
use App\Models\Observacion;
use App\Services\EntrevistaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorController extends Controller
{
    public function dashboard()
    {
        $tutor = Auth::user()->tutor;

        $asignaciones = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get();

        $riesgos = ['bajo' => 0, 'medio' => 0, 'alto' => 0];
        foreach ($asignaciones as $asignacion) {
            $ultima = $asignacion->entrevistas()->latest('fecha')->first();
            if ($ultima && isset($riesgos[$ultima->nivel_riesgo])) {
                $riesgos[$ultima->nivel_riesgo]++;
            }
        }

        $carreras = $asignaciones->pluck('estudiante.carrera')->unique()->values();
        $chartLabels = $carreras->toArray();
        $chartAlto = [];
        $chartMedio = [];
        $chartBajo = [];

        foreach ($carreras as $carrera) {
            $alto = $medio = $bajo = 0;
            foreach ($asignaciones->where('estudiante.carrera', $carrera) as $asignacion) {
                $ultima = $asignacion->entrevistas()->latest('fecha')->first();
                if (!$ultima) {
                    continue;
                }

                match ($ultima->nivel_riesgo) {
                    'alto' => $alto++,
                    'medio' => $medio++,
                    'bajo' => $bajo++,
                    default => null,
                };
            }

            $chartAlto[] = $alto;
            $chartMedio[] = $medio;
            $chartBajo[] = $bajo;
        }

        $ultimasEntrevistas = Entrevista::with('asignacion.estudiante.user')
            ->whereIn('asignacion_id', $asignaciones->pluck('id'))
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get()
            ->map(fn ($entrevista) => (object) [
                'nombre' => $entrevista->asignacion->estudiante->user->name,
                'carrera' => $entrevista->asignacion->estudiante->carrera,
                'fecha' => $entrevista->fecha,
                'puntaje_total' => $entrevista->puntaje_total,
                'nivel_riesgo' => $entrevista->nivel_riesgo,
            ]);

        return view('tutor.dashboard', compact(
            'riesgos',
            'chartLabels',
            'chartAlto',
            'chartMedio',
            'chartBajo',
            'ultimasEntrevistas'
        ));
    }

    public function misEstudiantes()
    {
        $tutor = Auth::user()->tutor;
        $asignaciones = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get();

        $estudiantes = $asignaciones->map(function ($asignacion) {
            $ultimaEntrevista = $asignacion->entrevistas()->latest('fecha')->first();

            return (object) [
                'id' => $asignacion->estudiante->id,
                'nombre' => $asignacion->estudiante->user->name,
                'codigo' => $asignacion->estudiante->codigo,
                'carrera' => $asignacion->estudiante->carrera,
                'ciclo' => $asignacion->estudiante->ciclo,
                'grupo' => $asignacion->estudiante->grupo,
                'edad' => $asignacion->estudiante->edad,
                'nivel_riesgo' => $ultimaEntrevista?->nivel_riesgo ?? 'sin entrevista',
                'fecha_ultima' => $ultimaEntrevista?->fecha,
            ];
        });

        return view('tutor.estudiantes', compact('estudiantes'));
    }

    public function nuevaEntrevista(Estudiante $estudiante)
    {
        $tutor = Auth::user()->tutor;
        $asignacion = Asignacion::where('tutor_id', $tutor->id)
            ->where('estudiante_id', $estudiante->id)
            ->firstOrFail();
        $formAction = route('tutor.guardar');

        return view('tutor.entrevista.create', compact('asignacion', 'estudiante', 'formAction'));
    }

    public function guardarEntrevista(Request $request)
    {
        $data = $request->validate([
            'asignacion_id' => 'required|exists:asignaciones,id',
            'fecha' => 'required|date',
            'carrera' => 'nullable|string|max:100',
            'ciclo' => 'nullable|string|max:20',
            'grupo' => 'nullable|string|max:20',
            'edad' => 'nullable|integer|min:10|max:80',
            'acad_2' => 'required|integer|min:1|max:3',
            'emoc_2' => 'required|integer|min:1|max:3',
            'soc_2' => 'required|integer|min:1|max:3',
            'econ_2' => 'required|integer|min:1|max:3',
            'fam_2' => 'required|integer|min:1|max:3',
            'salud_2' => 'required|integer|min:1|max:3',
            'documento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'observacion' => 'nullable|string',
        ]);

        $asignacion = Asignacion::findOrFail($data['asignacion_id']);
        abort_if($asignacion->tutor_id !== Auth::user()->tutor->id, 403, 'No tienes permiso sobre este estudiante.');

        $resultado = app(EntrevistaService::class)->registrar($data, $request->file('documento'));

        return redirect()->route('tutor.estudiantes')
            ->with('success', 'Encuesta registrada. Puntaje: ' . $resultado['puntaje'] . '. Nivel de riesgo: ' . strtoupper($resultado['nivel']))
            ->with('recomendacion', $resultado['recomendacion']);
    }

    public function historial(Estudiante $estudiante)
    {
        $tutor = Auth::user()->tutor;
        $asignacion = Asignacion::where('tutor_id', $tutor->id)
            ->where('estudiante_id', $estudiante->id)
            ->firstOrFail();

        $entrevistas = Entrevista::with('observacion')
            ->where('asignacion_id', $asignacion->id)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('tutor.historial', compact('estudiante', 'entrevistas'));
    }

    public function observaciones()
    {
        $tutor = Auth::user()->tutor;

        $estudiantes = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get()
            ->map(function ($asignacion) {
                $ultimaEntrevista = $asignacion->entrevistas()->latest('fecha')->first();
                if (!$ultimaEntrevista) {
                    return null;
                }

                return (object) [
                    'id' => $asignacion->estudiante->id,
                    'entrevista_id' => $ultimaEntrevista->id,
                    'nombre' => $asignacion->estudiante->user->name,
                    'carrera' => $asignacion->estudiante->carrera . ' - ' . $asignacion->estudiante->ciclo,
                    'nivel_riesgo' => $ultimaEntrevista->nivel_riesgo,
                ];
            })
            ->filter()
            ->values();

        return view('tutor.observaciones', compact('estudiantes'));
    }

    public function guardarObservacion(Request $request)
    {
        $request->validate([
            'entrevista_id' => 'required|exists:entrevistas,id',
            'observacion' => 'required|string',
        ]);

        $tutor = Auth::user()->tutor;
        $entrevista = Entrevista::with('asignacion.estudiante')->findOrFail($request->entrevista_id);

        abort_if($entrevista->asignacion->tutor_id !== $tutor->id, 403, 'No tienes permiso sobre este estudiante.');

        Observacion::updateOrCreate(
            ['entrevista_id' => $entrevista->id],
            ['texto' => $request->observacion]
        );

        AuditLog::registrar('create', 'Observacion', $entrevista->id, [
            'estudiante' => $entrevista->asignacion->estudiante->codigo,
        ]);

        return redirect()->route('tutor.observaciones')
            ->with('success', 'Observacion registrada correctamente.');
    }

    public function alertas()
    {
        $tutor = Auth::user()->tutor;
        $asignaciones = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get();

        $derivaciones = Derivacion::where('tutor_id', $tutor->id)->get();
        $estudiantesYaDerivados = $derivaciones->pluck('estudiante_id')->unique();

        $estudiantesRiesgoAlto = $asignaciones
            ->map(function ($asignacion) {
                $ultima = $asignacion->entrevistas()->latest('fecha')->first();
                if (!$ultima || $ultima->nivel_riesgo !== 'alto') {
                    return null;
                }

                return (object) [
                    'estudiante_id' => $asignacion->estudiante->id,
                    'nombre' => $asignacion->estudiante->user->name,
                    'carrera' => $asignacion->estudiante->carrera . ' - ' . $asignacion->estudiante->ciclo,
                    'fecha' => $ultima->fecha,
                    'puntaje_total' => $ultima->puntaje_total,
                ];
            })
            ->filter()
            ->values();

        $sinDerivar = $estudiantesRiesgoAlto
            ->reject(fn ($estudiante) => $estudiantesYaDerivados->contains($estudiante->estudiante_id))
            ->values();

        $derivacionesPendientes = Derivacion::with('estudiante.user')
            ->where('tutor_id', $tutor->id)
            ->where('estado', 'pendiente')
            ->orderBy('fecha_derivacion', 'desc')
            ->get();

        $resumen = [
            'sin_derivar' => $sinDerivar->count(),
            'pendientes' => $derivacionesPendientes->count(),
            'resueltas' => $derivaciones
                ->where('estado', 'completado')
                ->filter(fn ($derivacion) => $derivacion->fecha_respuesta && $derivacion->fecha_respuesta->isCurrentMonth())
                ->count(),
        ];

        return view('tutor.alertas', compact('sinDerivar', 'derivacionesPendientes', 'resumen'));
    }
}
