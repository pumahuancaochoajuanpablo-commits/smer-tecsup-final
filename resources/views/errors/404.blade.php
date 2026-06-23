@include('errors.layout', [
    'statusCode' => 404,
    'title' => 'Pagina no encontrada',
    'message' => 'La ruta solicitada no existe o fue movida dentro del sistema.',
    'suggestion' => 'Revisa la direccion, vuelve al inicio o abre la opcion correcta desde el menu principal.',
])
