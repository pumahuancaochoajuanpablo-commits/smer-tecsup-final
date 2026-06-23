@include('errors.layout', [
    'statusCode' => 503,
    'title' => 'Servicio no disponible',
    'message' => 'El sistema esta en mantenimiento o no puede atender solicitudes temporalmente.',
    'suggestion' => 'Espera un momento y vuelve a intentarlo.',
])
