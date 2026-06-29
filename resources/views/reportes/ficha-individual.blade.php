<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Individual - {{ $estudiante->codigo }}</title>
    <style>
        @page { size: A4 landscape; margin: 24px; }
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #366092; padding-bottom: 15px; }
        .header h1 { margin: 0; color: #366092; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        .section { margin-bottom: 25px; page-break-inside: avoid; }
        .section-title { background: #f0f0f0; padding: 10px; font-weight: bold; color: #366092; border-left: 4px solid #366092; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #366092; color: white; }
        .historial-table { table-layout: fixed; font-size: 11px; }
        .historial-table th, .historial-table td { padding: 7px 6px; line-height: 1.25; }
        .historial-table .fecha { width: 12%; }
        .historial-table .indicador { width: 9.5%; text-align: center; }
        .historial-table .puntaje { width: 9%; text-align: center; }
        .historial-table .riesgo { width: 11%; text-align: center; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-alto { background: #dc3545; color: white; }
        .badge-medio { background: #ffc107; color: black; }
        .badge-bajo { background: #28a745; color: white; }
        .info-box { background: #e8f4f8; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 15px; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FICHA INDIVIDUAL DE ESTUDIANTE</h1>
        <p>Sistema de Gestion y Seguimiento de Entrevistas (SMER)</p>
    </div>

    <div class="section">
        <div class="section-title">DATOS PERSONALES</div>
        <table>
            <tr>
                <th>Nombre</th>
                <td>{{ $estudiante->user->name }}</td>
                <th>Codigo</th>
                <td>{{ $estudiante->codigo }}</td>
            </tr>
            <tr>
                <th>Carrera</th>
                <td>{{ $estudiante->carrera }}</td>
                <th>Ciclo</th>
                <td>{{ $estudiante->ciclo ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Correo</th>
                <td colspan="3">{{ $estudiante->user->email }}</td>
            </tr>
        </table>
    </div>

    @if($asignacion)
    <div class="section">
        <div class="section-title">INFORMACION DE TUTORIA</div>
        <table>
            <tr>
                <th>Tutor Asignado</th>
                <td>{{ $asignacion->tutor->user->name }}</td>
                <th>Especialidad</th>
                <td>{{ $asignacion->tutor->especialidad }}</td>
            </tr>
            <tr>
                <th>Fecha de Inicio</th>
                <td>{{ $asignacion->fecha_inicio->format('d/m/Y') }}</td>
                <th>Estado</th>
                <td>{{ ucfirst($asignacion->estado) }}</td>
            </tr>
        </table>
    </div>
    @endif

    @if($entrevistas->count() > 0)
    <div class="section">
        <div class="section-title">RESUMEN DE ENTREVISTAS</div>
        <table>
            <tr>
                <th>Total de Entrevistas</th>
                <td>{{ $resumen['total'] }}</td>
                <th>Puntaje Promedio</th>
                <td>{{ $resumen['promedio_puntaje'] }}/18</td>
            </tr>
            <tr>
                <th>Riesgo Predominante</th>
                <td><span class="badge badge-{{ strtolower($resumen['riesgo_predominante']) }}">{{ $resumen['riesgo_predominante'] }}</span></td>
                <th>Tendencia</th>
                <td>{{ $resumen['tendencia'] }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">HISTORIAL DE ENTREVISTAS</div>
        <table class="historial-table">
            <thead>
                <tr>
                    <th class="fecha">Fecha</th>
                    <th class="indicador">Academico</th>
                    <th class="indicador">Emocional</th>
                    <th class="indicador">Social</th>
                    <th class="indicador">Economico</th>
                    <th class="indicador">Familiar</th>
                    <th class="indicador">Salud</th>
                    <th class="puntaje">Puntaje</th>
                    <th class="riesgo">Riesgo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entrevistas as $entrevista)
                <tr>
                    <td class="fecha">{{ $entrevista->fecha->format('d/m/Y') }}</td>
                    <td class="indicador">{{ $entrevista->acad_2 }}</td>
                    <td class="indicador">{{ $entrevista->emoc_2 }}</td>
                    <td class="indicador">{{ $entrevista->soc_2 }}</td>
                    <td class="indicador">{{ $entrevista->econ_2 }}</td>
                    <td class="indicador">{{ $entrevista->fam_2 }}</td>
                    <td class="indicador">{{ $entrevista->salud_2 }}</td>
                    <td class="puntaje">{{ $entrevista->puntaje_total }}/18</td>
                    <td class="riesgo"><span class="badge badge-{{ $entrevista->nivel_riesgo }}">{{ strtoupper($entrevista->nivel_riesgo) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="info-box">
        <strong>Informacion:</strong> Este estudiante no tiene entrevistas registradas aun.
    </div>
    @endif

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>&copy; Sistema SMER - Gestion y Seguimiento de Entrevistas Estudiantiles</p>
    </div>
</body>
</html>
