<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Entrevista;
use App\Models\Estudiante;
use App\Models\ParametroRiesgo;
use App\Models\Tutor;
use App\Models\User;
use App\Services\EntrevistaService;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEstudiantes = Estudiante::count();
        $totalTutores = Tutor::count();
        $totalEntrevistas = Entrevista::count();

        $riesgos = Entrevista::selectRaw('nivel_riesgo, COUNT(*) as total')
            ->groupBy('nivel_riesgo')
            ->pluck('total', 'nivel_riesgo');

        $ultimasEntrevistas = Entrevista::with(['asignacion.estudiante.user', 'asignacion.tutor.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard-new', compact(
            'totalEstudiantes',
            'totalTutores',
            'totalEntrevistas',
            'riesgos',
            'ultimasEntrevistas'
        ));
    }

    public function tutores()
    {
        $tutores = Tutor::with('user')->get();

        return view('admin.tutores.index', compact('tutores'));
    }

    public function guardarTutor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'codigo' => 'required|unique:tutores,codigo',
            'especialidad' => 'required|string|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('tutor123'),
            'rol_id' => 2,
            'estado' => true,
        ]);

        Tutor::create([
            'user_id' => $user->id,
            'codigo' => $request->codigo,
            'especialidad' => $request->especialidad,
        ]);

        return redirect()->route('admin.tutores')->with('success', 'Tutor registrado correctamente.');
    }

    public function importarForm()
    {
        return view('admin.estudiantes.importar');
    }

    public function importarCSV(Request $request)
    {
        $request->validate(['archivo' => 'required|file|mimes:csv,txt']);

        $handle = fopen($request->file('archivo')->getRealPath(), 'r');
        $headers = fgetcsv($handle, 0, ',');
        $importados = 0;
        $errores = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $data = array_combine($headers, $row);

            try {
                $nombre = $data['nombre'] ?? trim(($data['apellidos'] ?? '') . ' ' . ($data['nombres'] ?? ''));

                $user = User::create([
                    'name' => $nombre,
                    'email' => $data['email'],
                    'password' => Hash::make('estudiante123'),
                    'rol_id' => 3,
                    'estado' => true,
                ]);

                Estudiante::create([
                    'user_id' => $user->id,
                    'codigo' => $data['codigo'] ?? 'EST' . str_pad((string) ($user->id), 4, '0', STR_PAD_LEFT),
                    'carrera' => $data['carrera'] ?? $data['especialidad'] ?? 'No especificada',
                    'ciclo' => $data['ciclo'] ?? $data['semestre'] ?? null,
                    'grupo' => $data['grupo'] ?? null,
                    'edad' => $data['edad'] ?? null,
                    'estado' => true,
                ]);

                $importados++;
            } catch (\Throwable $exception) {
                $errores[] = 'Fila ' . ($importados + 2) . ': ' . $exception->getMessage();
            }
        }

        fclose($handle);

        return redirect()->route('admin.importar')
            ->with('success', "$importados estudiantes importados.")
            ->with('errores', $errores);
    }

    public function asignacionesForm()
    {
        $tutores = Tutor::with('user')->get();
        $estudiantes = Estudiante::with('user')
            ->whereDoesntHave('asignaciones')
            ->orderBy('codigo')
            ->get();
        $asignaciones = Asignacion::with(['tutor.user', 'estudiante.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.asignaciones.index', compact('tutores', 'estudiantes', 'asignaciones'));
    }

    public function asignarTutoria(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutores,id',
            'estudiantes' => 'required|array|min:1',
            'estudiantes.*' => 'exists:estudiantes,id',
        ]);

        foreach ($request->estudiantes as $estudianteId) {
            Asignacion::firstOrCreate([
                'tutor_id' => $request->tutor_id,
                'estudiante_id' => $estudianteId,
            ], [
                'fecha_inicio' => now(),
            ]);
        }

        return redirect()->route('admin.asignaciones')->with('success', 'Tutorias asignadas correctamente.');
    }

    public function encuestas()
    {
        $asignaciones = Asignacion::with(['tutor.user', 'estudiante.user', 'entrevistas'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.encuestas.index', compact('asignaciones'));
    }

    public function nuevaEncuesta(Asignacion $asignacion)
    {
        $asignacion->load(['estudiante.user', 'tutor.user']);
        $estudiante = $asignacion->estudiante;
        $formAction = route('admin.encuestas.guardar');

        return view('tutor.entrevista.create', compact('asignacion', 'estudiante', 'formAction'));
    }

    public function guardarEncuesta(Request $request)
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

        $resultado = app(EntrevistaService::class)->registrar($data, $request->file('documento'));

        return redirect()->route('admin.encuestas.index')
            ->with('success', 'Encuesta registrada. Puntaje: ' . $resultado['puntaje'] . '. Nivel de riesgo: ' . strtoupper($resultado['nivel']))
            ->with('recomendacion', $resultado['recomendacion']);
    }

    public function configuracion()
    {
        $parametros = ParametroRiesgo::all()->keyBy('indicador');

        return view('admin.configuracion.index', compact('parametros'));
    }

    public function guardarConfig(Request $request)
    {
        $request->validate([
            'umbral_bajo_global' => 'required|integer|min:1|max:18',
            'umbral_medio_global' => 'required|integer|min:1|max:18',
            'umbral_alto_global' => 'required|integer|min:1|max:18',
        ]);

        if ($request->umbral_bajo_global >= $request->umbral_medio_global || $request->umbral_medio_global >= $request->umbral_alto_global) {
            return back()
                ->withInput()
                ->withErrors(['umbrales' => 'Los umbrales deben mantener este orden: bajo menor que medio, y medio menor que alto.']);
        }

        foreach (['acad_2', 'emoc_2', 'soc_2', 'econ_2', 'fam_2', 'salud_2'] as $indicador) {
            ParametroRiesgo::updateOrCreate(['indicador' => $indicador], [
                'peso' => 1,
                'umbral_bajo' => $request->umbral_bajo_global,
                'umbral_medio' => $request->umbral_medio_global,
                'umbral_alto' => $request->umbral_alto_global,
            ]);
        }

        return redirect()->route('admin.config')->with('success', 'Configuracion actualizada.');
    }

    public function fichaIndividualPDF(Estudiante $estudiante)
    {
        return app(ReporteService::class)->fichaIndividualPDF($estudiante);
    }

    public function informeGeneralPDF()
    {
        return app(ReporteService::class)->informeGeneralPDF();
    }

    public function exportarExcel()
    {
        return app(ReporteService::class)->exportarEntrevistasExcel();
    }

    public function exportarFichasMasivas()
    {
        return app(ReporteService::class)->exportarFichasMasivasZip();
    }
}
