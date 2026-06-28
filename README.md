# SMER Tecsup

Sistema de Monitoreo de Riesgo Estudiantil orientado al registro de encuestas, calculo de riesgo, seguimiento tutorial, derivaciones a Bienestar Estudiantil y generacion de reportes.

## Modulos principales

- Autenticacion por roles: administrador, tutor y estudiante.
- Registro e importacion de estudiantes.
- Asignacion de tutores a estudiantes.
- Encuestas de riesgo estudiantil con puntaje automatico.
- Observaciones por encuesta.
- Alertas y derivaciones de casos de riesgo alto.
- Reportes PDF, exportacion Excel y fichas masivas.
- Auditoria de acciones relevantes del sistema.

## Tecnologias

- PHP 8.2 o superior.
- Laravel 12.
- PostgreSQL en produccion.
- Vite, Tailwind CSS y Alpine.js para la interfaz.
- DomPDF para reportes PDF.
- Laravel Excel para exportaciones.
- Render para despliegue con Docker.

## Instalacion local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

## Despliegue

El proyecto incluye `render.yaml`, `Dockerfile` y scripts de arranque para crear el servicio web y conectarlo a una base PostgreSQL administrada por Render.
