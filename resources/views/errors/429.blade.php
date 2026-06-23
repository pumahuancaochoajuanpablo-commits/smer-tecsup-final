@include('errors.layout', [
    'statusCode' => 429,
    'title' => 'Demasiados intentos',
    'message' => 'El sistema recibio muchas solicitudes en poco tiempo y bloqueo temporalmente esta accion.',
    'suggestion' => 'Espera unos minutos antes de intentarlo nuevamente.',
])
