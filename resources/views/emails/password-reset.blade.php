<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Restablecer contrasena</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0C2333; line-height: 1.5;">
    <h1 style="font-size: 22px;">Restablecer contrasena</h1>

    <p>Hola {{ $user->name }},</p>

    <p>Recibimos una solicitud para restablecer la contrasena de tu cuenta en SMER Tecsup.</p>

    <p>
        <a href="{{ $resetUrl }}" style="display: inline-block; background: #0CB9D7; color: #ffffff; padding: 12px 18px; border-radius: 6px; text-decoration: none; font-weight: bold;">
            Restablecer contrasena
        </a>
    </p>

    <p>Si el boton no funciona, copia y pega este enlace en tu navegador:</p>
    <p style="word-break: break-all;">{{ $resetUrl }}</p>

    <p>Si no solicitaste este cambio, puedes ignorar este mensaje.</p>

    <p>SMER Tecsup</p>
</body>
</html>
