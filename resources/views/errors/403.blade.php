@include('errors.layout', [
    'statusCode' => 403,
    'title' => 'No tienes permiso para acceder',
    'message' => 'Tu usuario esta autenticado, pero no tiene el rol o permiso necesario para abrir esta seccion.',
    'suggestion' => 'Verifica que ingresaste con la cuenta correcta. Si necesitas acceso, solicita al administrador que revise tu rol.',
])
