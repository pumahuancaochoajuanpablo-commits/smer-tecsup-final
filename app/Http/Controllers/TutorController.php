<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\AuditLog;
use App\Models\Derivacion;
use App\Models\Entrevista;
use App\Models\Estudiante;
use App\Models\Notificacion;
use App\Models\Observacion;
use App\Models\ParametroRiesgo;
use App\Models\Recomendacion;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorController extends Controller
{
    // ── DASHBOARD TUTOR ──────────────────────────────────────────
    public function dashboard()
    {
        $tutor = Auth::user()->tutor;

        $asignaciones = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get();

        // Conteo de riesgos basado en la última entrevista de cada estudiante
        $riesgos = ['bajo' => 0, 'medio' => 0, 'alto' => 0];
        foreach ($asignaciones as $asig) {
            $ultima = $asig->entrevistas()->latest()->first();
            if ($ultima && isset($riesgos[$ultima->nivel_riesgo])) {
                $riesgos[$ultima->nivel_riesgo]++;
            }
        }

        // Datos para el gráfico: agrupar entrevistas por carrera
        $carreras = $asignaciones->pluck('estudiante.carrera')->unique()->values();
        $chartLabels = $carreras->toArray();
        $chartAlto   = [];
        $chartMedio  = [];
        $chartBajo   = [];

        foreach ($carreras as $carrera) {
            $asigCarrera = $asignaciones->filter(fn($a) => $a->estudiante->carrera === $carrera);
            $alto = $medio = $bajo = 0;
            foreach ($asigCarrera as $asig) {
                $ultima = $asig->entrevistas()->latest()->first();
                if (!$ultima) continue;
                match($ultima->nivel_riesgo) {
                    'alto'  => $alto++,
                    'medio' => $medio++,
                    'bajo'  => $bajo++,
                    default => null,
                };
            }
            $chartAlto[]  = $alto;
            $chartMedio[] = $medio;
            $chartBajo[]  = $bajo;
        }

        // Últimas 10 entrevistas del tutor
        $asignacionIds = $asignaciones->pluck('id');
        $ultimasEntrevistas = Entrevista::with('asignacion.estudiante.user')
            ->whereIn('asignacion_id', $asignacionIds)
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($e) => (object)[
                'nombre'       => $e->asignacion->estudiante->user->name,
                'carrera'      => $e->asignacion->estudiante->carrera,
                'fecha'        => $e->fecha,
                'puntaje_total'=> $e->puntaje_total,
                'nivel_riesgo' => $e->nivel_riesgo,
            ]);

        return view('tutor.dashboard', compact(
            'riesgos', 'chartLabels', 'chartAlto', 'chartMedio', 'chartBajo', 'ultimasEntrevistas'
        ));
    }

    // ── MIS ESTUDIANTES ───────────────────────────────────────────
    public function misEstudiantes()
    {
        $tutor = Auth::user()->tutor;
        $asignaciones = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get();

        $estudiantes = $asignaciones->map(function ($asignacion) {
            $ultimaEntrevista = $asignacion->entrevistas()->latest()->first();
            return (object) [
                'id'           => $asignacion->estudiante->id,
                'nombre'       => $asignacion->estudiante->user->name,
                'codigo'       => $asignacion->estudiante->codigo,
                'carrera'      => $asignacion->estudiante->carrera,
                'ciclo'        => $asignacion->estudiante->ciclo,
                'nivel_riesgo' => $ultimaEntrevista?->nivel_riesgo ?? 'sin entrevista',
                'fecha_ultima' => $ultimaEntrevista?->fecha,
            ];
        });

        return view('tutor.estudiantes', compact('estudiantes'));
    }

    // ── NUEVA ENTREVISTA ──────────────────────────────────────────
    public function nuevaEntrevista(Estudiante $estudiante)
    {
        $tutor = Auth::user()->tutor;
        $asignacion = Asignacion::where('tutor_id', $tutor->id)
            ->where('estudiante_id', $estudiante->id)
            ->firstOrFail();

        return view('tutor.entrevista.create', compact('asignacion', 'estudiante'));
    }

    // ── GUARDAR ENTREVISTA ────────────────────────────────────────
    public function guardarEntrevista(Request $request)
    {
        $request->validate([
            'asignacion_id' => 'required|exists:asignaciones,id',
            'fecha'         => 'required|date',
            'acad_2'        => 'required|integer|min:1|max:5',
            'emoc_2'        => 'required|integer|min:1|max:5',
            'soc_2'         => 'required|integer|min:1|max:5',
            'econ_2'        => 'required|integer|min:1|max:5',
            'fam_2'         => 'required|integer|min:1|max:5',
            'salud_2'       => 'required|integer|min:1|max:5',
            'documento'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'observacion'   => 'nullable|string',
        ]);

        $params  = ParametroRiesgo::all()->keyBy('indicador');
        $campos  = ['acad_2', 'emoc_2', 'soc_2', 'econ_2', 'fam_2', 'salud_2'];
        $puntaje = 0;

        foreach ($campos as $campo) {
            $invertido = 6 - $request->$campo;
            $puntaje  += $invertido * ($params[$campo]->peso / 100);
        }

        $ponderado   = ($puntaje / 5) * 10;
        $umbralAlto  = $params->first()->umbral_alto;
        $umbralMedio = $params->first()->umbral_medio;

        if ($ponderado <= 3) {
            $nivel = 'alto';
        } elseif ($ponderado <= 5) {
            $nivel = 'medio';
        } else {
            $nivel = 'bajo';
        }

        $datosEntrevista = [
            'asignacion_id' => $request->asignacion_id,
            'fecha'         => $request->fecha,
            'acad_2'        => $request->acad_2,
            'emoc_2'        => $request->emoc_2,
            'soc_2'         => $request->soc_2,
            'econ_2'        => $request->econ_2,
            'fam_2'         => $request->fam_2,
            'salud_2'       => $request->salud_2,
            'puntaje_total' => round($ponderado, 2),
            'nivel_riesgo'  => $nivel,
        ];

        if ($request->hasFile('documento')) {
            $datosEntrevista['documento'] = $request->file('documento')->store('entrevistas', 'public');
        }

        $entrevista = Entrevista::create($datosEntrevista);

        if ($request->filled('observacion')) {
            Observacion::create([
                'entrevista_id' => $entrevista->id,
                'texto'         => $request->observacion,
            ]);
        }

        AuditLog::registrar('create', 'Entrevista', $entrevista->id, [
            'asignacion_id' => $request->asignacion_id,
            'estudiante'    => $entrevista->asignacion->estudiante->codigo,
            'puntaje'       => $entrevista->puntaje_total,
            'nivel_riesgo'  => $nivel,
        ]);

        if ($nivel === 'alto') {
            $asignacion = $entrevista->asignacion;
            Notificacion::create([
                'estudiante_id' => $asignacion->estudiante_id,
                'mensaje'       => 'Alerta: Se ha detectado un nivel de riesgo ALTO en tu última entrevista. Bienestar Universitario será notificado.',
            ]);
        }

        $recomendacion = Recomendacion::where('nivel_riesgo', $nivel)->first();

        return redirect()->route('tutor.estudiantes')
            ->with('success', 'Entrevista registrada. Nivel de riesgo: ' . strtoupper($nivel))
            ->with('recomendacion', $recomendacion?->acciones);
    }

    // ── HISTORIAL ─────────────────────────────────────────────────
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

    // ── LISTA PARA OBSERVACIONES (solo estudiantes ya entrevistados) ──
    public function observaciones()
    {
        $tutor = Auth::user()->tutor;

        $asignaciones = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get();

        $estudiantes = $asignaciones
            ->map(function ($asignacion) {
                $ultimaEntrevista = $asignacion->entrevistas()->latest('fecha')->first();

                if (!$ultimaEntrevista) {
                    return null;
                }

                return (object) [
                    'id'             => $asignacion->estudiante->id,
                    'entrevista_id'  => $ultimaEntrevista->id,
                    'nombre'         => $asignacion->estudiante->user->name,
                    'carrera'        => $asignacion->estudiante->carrera . ' - ' . $asignacion->estudiante->ciclo,
                    'nivel_riesgo'   => $ultimaEntrevista->nivel_riesgo,
                ];
            })
            ->filter()
            ->values();

        return view('tutor.observaciones', compact('estudiantes'));
    }

    // ── GUARDAR OBSERVACIÓN ──────────────────────────────────────
    public function guardarObservacion(Request $request)
    {
        $request->validate([
            'entrevista_id' => 'required|exists:entrevistas,id',
            'observacion'   => 'required|string',
        ]);

        $tutor = Auth::user()->tutor;

        // Verifica que la entrevista pertenezca a un estudiante asignado a este tutor
        $entrevista = Entrevista::with('asignacion')->findOrFail($request->entrevista_id);

        if ($entrevista->asignacion->tutor_id !== $tutor->id) {
            abort(403, 'No tienes permiso sobre este estudiante.');
        }

        Observacion::updateOrCreate(
            ['entrevista_id' => $entrevista->id],
            ['texto' => $request->observacion]
        );

        AuditLog::registrar('create', 'Observacion', $entrevista->id, [
            'estudiante' => $entrevista->asignacion->estudiante->codigo,
        ]);

        return redirect()->route('tutor.observaciones')
            ->with('success', 'Observación registrada correctamente.');
    }

    // ── ALERTAS ──────────────────────────────────────────────────
    public function alertas()
    {
        $tutor = Auth::user()->tutor;

        $asignaciones = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get();

        // Derivaciones ya registradas por este tutor (cualquier estado)
        $derivaciones = Derivacion::where('tutor_id', $tutor->id)->get();
        $estudiantesYaDerivados = $derivaciones->pluck('estudiante_id')->unique();

        // Estudiantes con riesgo ALTO (según su última entrevista) que aún no tienen derivación
        $estudiantesRiesgoAlto = $asignaciones
            ->map(function ($asignacion) {
                $ultima = $asignacion->entrevistas()->latest('fecha')->first();
                if (!$ultima || $ultima->nivel_riesgo !== 'alto') {
                    return null;
                }
                return (object) [
                    'estudiante_id' => $asignacion->estudiante->id,
                    'nombre'        => $asignacion->estudiante->user->name,
                    'carrera'       => $asignacion->estudiante->carrera . ' - ' . $asignacion->estudiante->ciclo,
                    'fecha'         => $ultima->fecha,
                    'puntaje_total' => $ultima->puntaje_total,
                ];
            })
            ->filter()
            ->values();

        $sinDerivar = $estudiantesRiesgoAlto
            ->reject(fn ($e) => $estudiantesYaDerivados->contains($e->estudiante_id))
            ->values();

        // Derivaciones que el tutor envió y siguen pendientes de respuesta
        $derivacionesPendientes = Derivacion::with('estudiante.user')
            ->where('tutor_id', $tutor->id)
            ->where('estado', 'pendiente')
            ->orderBy('fecha_derivacion', 'desc')
            ->get();

        // Resumen del mes
        $resumen = [
            'sin_derivar' => $sinDerivar->count(),
            'pendientes'  => $derivacionesPendientes->count(),
            'resueltas'   => $derivaciones
                ->where('estado', 'completado')
                ->filter(fn ($d) => $d->fecha_respuesta && $d->fecha_respuesta->isCurrentMonth())
                ->count(),
        ];

        return view('tutor.alertas', compact('sinDerivar', 'derivacionesPendientes', 'resumen'));
    }
}