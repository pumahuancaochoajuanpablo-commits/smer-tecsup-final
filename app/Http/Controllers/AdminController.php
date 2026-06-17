<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Entrevista;
use App\Models\Estudiante;
use App\Models\ParametroRiesgo;
use App\Models\Tutor;
use App\Models\User;
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

        // Últimas 5 entrevistas
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

        $file = $request->file('archivo');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle, 0, ',');

        $importados = 0;
        $errores = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $data = array_combine($headers, $row);

            try {
                $user = User::create([
                    'name' => $data['nombre'],
                    'email' => $data['email'],
                    'password' => Hash::make('estudiante123'),
                    'rol_id' => 3,
                    'estado' => true,
                ]);

                Estudiante::create([
                    'user_id' => $user->id,
                    'codigo' => $data['codigo'],
                    'carrera' => $data['carrera'],
                    'estado' => true,
                ]);

                $importados++;
            } catch (\Exception $e) {
                $errores[] = "Fila " . ($importados + 2) . ": " . $e->getMessage();
            }
        }

        fclose($handle);

        return redirect()->route('admin.importar')
            ->with('success', "$importados estudiantes importados.")
            ->with('errores', $errores);
    }

    public function asignarTutoria(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutores,id',
            'estudiantes' => 'required|array',
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

        return redirect()->route('admin.asignaciones')->with('success', 'Tutorías asignadas correctamente.');
    }

    public function asignacionesForm()
    {
        $tutores = Tutor::with('user')->get();
        $estudiantes = Estudiante::with('user')
            ->whereDoesntHave('asignaciones')
            ->get();
        $asignaciones = Asignacion::with(['tutor.user', 'estudiante.user'])->get();

        return view('admin.asignaciones.index', compact('tutores', 'estudiantes', 'asignaciones'));
    }

    public function configuracion()
    {
        $parametros = ParametroRiesgo::all()->keyBy('indicador');
        return view('admin.configuracion.index', compact('parametros'));
    }

    public function guardarConfig(Request $request)
    {
        $request->validate([
            'peso.*' => 'required|numeric|min:0|max:100',
            'umbral_bajo.*' => 'required|integer|min:1',
            'umbral_medio.*' => 'required|integer|min:1',
            'umbral_alto.*' => 'required|integer|min:1',
        ]);

        foreach (['acad_2', 'emoc_2', 'soc_2', 'econ_2', 'fam_2', 'salud_2'] as $indicador) {
            ParametroRiesgo::where('indicador', $indicador)->update([
                'peso' => $request->peso[$indicador],
                'umbral_bajo' => $request->umbral_bajo[$indicador],
                'umbral_medio' => $request->umbral_medio[$indicador],
                'umbral_alto' => $request->umbral_alto[$indicador],
            ]);
        }

        return redirect()->route('admin.config')->with('success', 'Configuración actualizada.');
    }

    /**
     * CUS08: Generar ficha individual en PDF
     */
    public function fichaIndividualPDF(Estudiante $estudiante)
    {
        $reporteService = new ReporteService();
        return $reporteService->fichaIndividualPDF($estudiante);
    }

    /**
     * CUS08: Generar informe general en PDF
     */
    public function informeGeneralPDF()
    {
        $reporteService = new ReporteService();
        return $reporteService->informeGeneralPDF();
    }

    /**
     * CUS08: Exportar entrevistas a Excel
     */
    public function exportarExcel()
    {
        $reporteService = new ReporteService();
        return $reporteService->exportarEntrevistasExcel();
    }
}
