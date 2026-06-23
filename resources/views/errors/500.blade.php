@include('errors.layout', [
    'statusCode' => 500,
    'title' => 'Error interno del sistema',
    'message' => 'El servidor encontro un problema al procesar la solicitud. Puede deberse a datos incompletos, configuracion del entorno, base de datos o una excepcion no controlada.',
    'suggestion' => 'Intenta nuevamente. Si ocurre otra vez, revisa el archivo de logs en storage/logs para encontrar la causa tecnica exacta.',
])
