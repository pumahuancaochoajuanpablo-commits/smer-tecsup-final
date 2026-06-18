<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\AuditLog;
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
    public function misEstudiantes()
    {
        $tutor = Auth::user()->tutor;
        $asignaciones = Asignacion::with(['estudiante.user', 'entrevistas'])
            ->where('tutor_id', $tutor->id)
            ->get();

        $estudiantes = $asignaciones->map(function ($asignacion) {
            $ultimaEntrevista = $asignacion->entrevistas()->latest()->first();
            return (object) [
                'id' => $asignacion->estudiante->id,
                'nombre' => $asignacion->estudiante->user->name,
                'codigo' => $asignacion->estudiante->codigo,
                'carrera' => $asignacion->estudiante->carrera,
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

        return view('tutor.entrevista.create', compact('asignacion', 'estudiante'));
    }

    public function guardarEntrevista(Request $request)
    {
        $request->validate([
            'asignacion_id' => 'required|exists:asignaciones,id',
            'fecha' => 'required|date',
            'acad_2' => 'required|integer|min:1|max:5',
            'emoc_2' => 'required|integer|min:1|max:5',
            'soc_2' => 'required|integer|min:1|max:5',
            'econ_2' => 'required|integer|min:1|max:5',
            'fam_2' => 'required|integer|min:1|max:5',
            'salud_2' => 'required|integer|min:1|max:5',
            'documento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'observacion' => 'nullable|string',
        ]);

        $params = ParametroRiesgo::all()->keyBy('indicador');
        $campos = ['acad_2', 'emoc_2', 'soc_2', 'econ_2', 'fam_2', 'salud_2'];
        $puntaje = 0;

        foreach ($campos as $campo) {
            $invertido = 6 - $request->$campo;
            $puntaje += $invertido * ($params[$campo]->peso / 100);
        }

        $ponderado = ($puntaje / 5) * 10;
        $umbralAlto = $params->first()->umbral_alto;
        $umbralMedio = $params->first()->umbral_medio;

        if ($ponderado >= $umbralAlto) {
            $nivel = 'alto';
        } elseif ($ponderado >= $umbralMedio) {
            $nivel = 'medio';
        } else {
            $nivel = 'bajo';
        }

        $ponderado = ($puntaje / 5) * 10;
        $umbralAlto = $params->first()->umbral_alto;
        $umbralMedio = $params->first()->umbral_medio;

        // Invertir la lógica: puntuaciones ALTAS (5=excelente) = riesgo BAJO
        // puntuaciones BAJAS (1=muy bajo) = riesgo ALTO
        if ($ponderado <= 3) {
            $nivel = 'alto';
        } elseif ($ponderado <= 5) {
            $nivel = 'medio';
        } else {
            $nivel = 'bajo';
        }

        $datosEntrevista = [
            'asignacion_id' => $request->asignacion_id,
            'fecha' => $request->fecha,
            'acad_2' => $request->acad_2,
            'emoc_2' => $request->emoc_2,
            'soc_2' => $request->soc_2,
            'econ_2' => $request->econ_2,
            'fam_2' => $request->fam_2,
            'salud_2' => $request->salud_2,
            'puntaje_total' => round($ponderado, 2),
            'nivel_riesgo' => $nivel,
        ];

        if ($request->hasFile('documento')) {
            $datosEntrevista['documento'] = $request->file('documento')->store('entrevistas', 'public');
        }

        $entrevista = Entrevista::create($datosEntrevista);

        if ($request->filled('observacion')) {
            Observacion::create([
                'entrevista_id' => $entrevista->id,
                'texto' => $request->observacion,
            ]);
        }

        // CUS10: Registrar en auditoría
        AuditLog::registrar('create', 'Entrevista', $entrevista->id, [
            'asignacion_id' => $request->asignacion_id,
            'estudiante' => $entrevista->asignacion->estudiante->codigo,
            'puntaje' => $entrevista->puntaje_total,
            'nivel_riesgo' => $nivel,
        ]);

        if ($nivel === 'alto') {
            $asignacion = $entrevista->asignacion;
            Notificacion::create([
                'estudiante_id' => $asignacion->estudiante_id,
                'mensaje' => 'Alerta: Se ha detectado un nivel de riesgo ALTO en tu última entrevista. Bienestar Universitario será notificado.',
            ]);
        }

        $recomendacion = Recomendacion::where('nivel_riesgo', $nivel)->first();

        return redirect()->route('tutor.estudiantes')
            ->with('success', 'Entrevista registrada. Nivel de riesgo: ' . strtoupper($nivel))
            ->with('recomendacion', $recomendacion?->acciones);
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

    private function getTutorAsignaciones()
    {
        $tutor = Auth::user()->tutor;
        return Asignacion::where('tutor_id', $tutor->id)->pluck('id');
    }
}
