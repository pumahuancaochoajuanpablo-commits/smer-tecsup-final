@php
    $statusCode = $statusCode ?? 500;
    $title = $title ?? 'No se pudo completar la solicitud';
    $message = $message ?? 'Ocurrio un problema inesperado mientras el sistema procesaba la informacion.';
    $suggestion = $suggestion ?? 'Intenta nuevamente. Si el problema continua, revisa los datos ingresados o contacta al administrador del sistema.';
    $technicalMessage = isset($exception) ? trim($exception->getMessage()) : '';
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error {{ $statusCode }} - {{ config('app.name', 'SMER') }}</title>
    <style>
        :root { --tecsup-cyan: #0CB9D7; --tecsup-dark: #0C2333; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #f9fafb; color: #111827; font-family: Arial, sans-serif; }
        main { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 16px; }
        section { width: 100%; max-width: 720px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 2px rgba(15, 23, 42, .06); padding: 28px; }
        .header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; border-bottom: 1px solid #f3f4f6; padding-bottom: 20px; }
        .eyebrow { margin: 0; color: var(--tecsup-cyan); font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        h1 { margin: 8px 0 0; color: var(--tecsup-dark); font-size: clamp(24px, 4vw, 32px); line-height: 1.15; }
        h2 { margin: 0; color: #374151; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        p { color: #4b5563; line-height: 1.6; }
        .brand { flex-shrink: 0; border-radius: 6px; background: #f3f4f6; color: #4b5563; padding: 6px 10px; font-size: 13px; font-weight: 700; }
        .content { margin-top: 24px; display: grid; gap: 18px; }
        .technical { border: 1px solid #fde68a; background: #fffbeb; border-radius: 6px; padding: 14px; }
        .technical h2, .technical p { color: #78350f; }
        .actions { margin-top: 28px; display: flex; flex-wrap: wrap; gap: 12px; }
        .button { display: inline-flex; justify-content: center; border-radius: 6px; padding: 10px 16px; font-size: 14px; font-weight: 700; text-decoration: none; }
        .button-primary { background: var(--tecsup-dark); color: #fff; }
        .button-secondary { border: 1px solid #d1d5db; color: #374151; }
        @media (max-width: 520px) { section { padding: 22px; } .header { flex-direction: column; } .button { width: 100%; } }
    </style>
</head>
<body>
    <main>
        <section>
            <div class="header">
                <div>
                    <p class="eyebrow">Error {{ $statusCode }}</p>
                    <h1>{{ $title }}</h1>
                </div>
                <span class="brand">SMER</span>
            </div>

            <div class="content">
                <div>
                    <h2>Que ocurrio</h2>
                    <p>{{ $message }}</p>
                </div>

                <div>
                    <h2>Que puedes hacer</h2>
                    <p>{{ $suggestion }}</p>
                </div>

                @if(config('app.debug') && $technicalMessage !== '')
                    <div class="technical">
                        <h2>Detalle tecnico</h2>
                        <p>{{ $technicalMessage }}</p>
                    </div>
                @endif
            </div>

            <div class="actions">
                <a href="{{ url()->previous() }}" class="button button-primary">
                    Volver atras
                </a>
                <a href="{{ url('/') }}" class="button button-secondary">
                    Ir al inicio
                </a>
            </div>
        </section>
    </main>
</body>
</html>
