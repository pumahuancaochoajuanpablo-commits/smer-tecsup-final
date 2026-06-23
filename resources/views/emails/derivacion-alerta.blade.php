<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Nueva derivacion estudiantil</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0C2333; line-height: 1.5;">
    <h1 style="font-size: 22px;">Nueva derivacion estudiantil</h1>

    <p>Se registro una derivacion para seguimiento de Bienestar Estudiantil.</p>

    <ul>
        <li><strong>Estudiante:</strong> {{ $estudianteNombre }}</li>
        <li><strong>Codigo:</strong> {{ $estudianteCodigo }}</li>
        <li><strong>Tutor:</strong> {{ $tutorNombre }}</li>
        <li><strong>Motivo:</strong> {{ $motivo }}</li>
        <li><strong>Recomendacion:</strong> Derivar a psicologia o bienestar estudiantil para evaluacion prioritaria.</li>
    </ul>

    <p><strong>Descripcion registrada:</strong></p>
    <p>{{ $descripcion }}</p>

    <p>SMER Tecsup</p>
</body>
</html>
