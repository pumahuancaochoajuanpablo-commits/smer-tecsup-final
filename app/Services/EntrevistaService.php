<?php

namespace App\Services;

use App\Models\Asignacion;
use App\Models\AuditLog;
use App\Models\Entrevista;
use App\Models\Notificacion;
use App\Models\Observacion;
use App\Models\ParametroRiesgo;
use App\Models\Recomendacion;
use Illuminate\Http\UploadedFile;

class EntrevistaService
{
    public const CAMPOS = ['acad_2', 'emoc_2', 'soc_2', 'econ_2', 'fam_2', 'salud_2'];

    public function registrar(array $data, ?UploadedFile $documento = null): array
    {
        $asignacion = Asignacion::with('estudiante.user')->findOrFail($data['asignacion_id']);
        $puntajeTotal = collect(self::CAMPOS)->sum(fn ($campo) => (int) $data[$campo]);
        $umbrales = $this->umbrales();
        $nivel = $this->nivelRiesgo($puntajeTotal, $umbrales);

        $datosEntrevista = [
            'asignacion_id' => $asignacion->id,
            'fecha' => $data['fecha'],
            'acad_2' => $data['acad_2'],
            'emoc_2' => $data['emoc_2'],
            'soc_2' => $data['soc_2'],
            'econ_2' => $data['econ_2'],
            'fam_2' => $data['fam_2'],
            'salud_2' => $data['salud_2'],
            'puntaje_total' => $puntajeTotal,
            'nivel_riesgo' => $nivel,
        ];

        if ($documento) {
            $datosEntrevista['documento'] = $documento->store('entrevistas', 'public');
        }

        $entrevista = Entrevista::create($datosEntrevista);

        if (!empty($data['observacion'])) {
            Observacion::create([
                'entrevista_id' => $entrevista->id,
                'texto' => $data['observacion'],
            ]);
        }

        $this->actualizarDatosAlumno($asignacion, $data);
        $this->registrarAuditoria($entrevista, $puntajeTotal, $nivel);
        $this->notificarSiEsAlto($asignacion, $nivel);

        return [
            'entrevista' => $entrevista,
            'nivel' => $nivel,
            'puntaje' => $puntajeTotal,
            'recomendacion' => Recomendacion::where('nivel_riesgo', $nivel)->first()?->acciones,
        ];
    }

    public function umbrales(): array
    {
        $parametro = ParametroRiesgo::query()->first();

        return [
            'bajo' => (int) ($parametro?->umbral_bajo ?? 7),
            'medio' => (int) ($parametro?->umbral_medio ?? 8),
            'alto' => (int) ($parametro?->umbral_alto ?? 14),
        ];
    }

    private function nivelRiesgo(int $puntajeTotal, array $umbrales): string
    {
        if ($puntajeTotal >= $umbrales['alto']) {
            return 'alto';
        }

        if ($puntajeTotal >= $umbrales['medio']) {
            return 'medio';
        }

        return 'bajo';
    }

    private function actualizarDatosAlumno(Asignacion $asignacion, array $data): void
    {
        $asignacion->estudiante->update([
            'carrera' => $data['carrera'] ?? $asignacion->estudiante->carrera,
            'ciclo' => $data['ciclo'] ?? $asignacion->estudiante->ciclo,
            'grupo' => $data['grupo'] ?? $asignacion->estudiante->grupo,
            'edad' => $data['edad'] ?? $asignacion->estudiante->edad,
        ]);
    }

    private function registrarAuditoria(Entrevista $entrevista, int $puntajeTotal, string $nivel): void
    {
        AuditLog::registrar('create', 'Entrevista', $entrevista->id, [
            'asignacion_id' => $entrevista->asignacion_id,
            'estudiante' => $entrevista->asignacion->estudiante->codigo,
            'puntaje' => $puntajeTotal,
            'nivel_riesgo' => $nivel,
        ]);
    }

    private function notificarSiEsAlto(Asignacion $asignacion, string $nivel): void
    {
        if ($nivel !== 'alto') {
            return;
        }

        Notificacion::create([
            'estudiante_id' => $asignacion->estudiante_id,
            'mensaje' => 'Alerta: se detecto riesgo alto en la ultima entrevista. Bienestar Estudiantil debe revisar el caso.',
        ]);
    }
}
