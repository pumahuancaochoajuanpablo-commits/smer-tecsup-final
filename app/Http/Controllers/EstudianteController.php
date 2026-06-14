<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Entrevista;
use App\Models\Notificacion;
use App\Models\Recomendacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudianteController extends Controller
{
    public function miEstado()
    {
        $estudiante = Auth::user()->estudiante;
        $asignacion = Asignacion::with('tutor.user')
            ->where('estudiante_id', $estudiante->id)
            ->first();

        $ultimaEntrevista = null;
        $recomendacion = null;
        $historial = collect();

        if ($asignacion) {
            $ultimaEntrevista = Entrevista::where('asignacion_id', $asignacion->id)
                ->latest()
                ->first();

            $historial = Entrevista::where('asignacion_id', $asignacion->id)
                ->orderBy('fecha', 'desc')
                ->take(5)
                ->get();

            if ($ultimaEntrevista) {
                $recomendacion = Recomendacion::where('nivel_riesgo', $ultimaEntrevista->nivel_riesgo)->first();
            }
        }

        return view('estudiante.estado', compact(
            'estudiante', 'asignacion', 'ultimaEntrevista', 'recomendacion', 'historial'
        ));
    }

    public function notificaciones()
    {
        $estudiante = Auth::user()->estudiante;
        $notificaciones = Notificacion::where('estudiante_id', $estudiante->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('estudiante.notificaciones', compact('notificaciones'));
    }

    public function marcarLeido(Request $request)
    {
        $estudiante = Auth::user()->estudiante;
        Notificacion::where('estudiante_id', $estudiante->id)
            ->where('id', $request->id)
            ->update(['leido' => true]);

        return response()->json(['success' => true]);
    }
}
