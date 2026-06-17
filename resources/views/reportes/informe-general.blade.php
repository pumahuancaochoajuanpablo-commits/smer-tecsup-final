<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe General de Entrevistas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #366092;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #366092;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            background: #f0f0f0;
            padding: 10px;
            font-weight: bold;
            color: #366092;
            border-left: 4px solid #366092;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-box.alto {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        .stat-box.medio {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }
        .stat-box.bajo {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            display: block;
        }
        .stat-label {
            font-size: 14px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #366092;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-alto {
            background: #dc3545;
            color: white;
        }
        .badge-medio {
            background: #ffc107;
            color: black;
        }
        .badge-bajo {
            background: #28a745;
            color: white;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INFORME GENERAL DE ENTREVISTAS</h1>
        <p>Sistema de Gestión y Seguimiento de Entrevistas Estudiantiles (SMER)</p>
        <p>Reporte generado: {{ $fecha_generacion }}</p>
    </div>

    <div class="section">
        <div class="section-title">📊 ESTADÍSTICAS GENERALES</div>
        <div class="stats-grid">
            <div class="stat-box">
                <span class="stat-number">{{ $total }}</span>
                <span class="stat-label">Total de Entrevistas</span>
            </div>
            <div class="stat-box alto">
                <span class="stat-number">{{ $riesgos['alto'] }}</span>
                <span class="stat-label">Riesgo Alto</span>
            </div>
            <div class="stat-box medio">
                <span class="stat-number">{{ $riesgos['medio'] }}</span>
                <span class="stat-label">Riesgo Medio</span>
            </div>
            <div class="stat-box bajo">
                <span class="stat-number">{{ $riesgos['bajo'] }}</span>
                <span class="stat-label">Riesgo Bajo</span>
            </div>
        </div>
    </div>

    @if($total > 0)
    <div class="section">
        <div class="section-title">📋 DETALLE DE ENTREVISTAS RECIENTES</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Estudiante</th>
                    <th>Código</th>
                    <th>Tutor</th>
                    <th>Puntaje</th>
                    <th>Riesgo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entrevistas->take(50) as $entrevista)
                <tr>
                    <td>{{ $entrevista->fecha->format('d/m/Y') }}</td>
                    <td>{{ $entrevista->asignacion->estudiante->user->name ?? 'N/A' }}</td>
                    <td>{{ $entrevista->asignacion->estudiante->codigo ?? 'N/A' }}</td>
                    <td>{{ $entrevista->asignacion->tutor->user->name ?? 'N/A' }}</td>
                    <td>{{ $entrevista->puntaje_total }}/10</td>
                    <td>
                        <span class="badge badge-{{ $entrevista->nivel_riesgo }}">
                            {{ strtoupper($entrevista->nivel_riesgo) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">No hay entrevistas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($total > 50)
            <p style="color: #999; font-size: 12px;">Mostrando 50 de {{ $total }} registros totales</p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>&copy; Sistema SMER - Gestión y Seguimiento de Entrevistas Estudiantiles</p>
    </div>
</body>
</html>
