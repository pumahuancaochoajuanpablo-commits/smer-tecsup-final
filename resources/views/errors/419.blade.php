@include('errors.layout', [
    'statusCode' => 419,
    'title' => 'La sesion expiro',
    'message' => 'El formulario ya no es valido porque la sesion vencio o el token de seguridad cambio.',
    'suggestion' => 'Vuelve a iniciar sesion, recarga la pagina y envia el formulario otra vez.',
])
