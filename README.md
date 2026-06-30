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
- MySQL con XAMPP y phpMyAdmin para ejecucion local.
- PostgreSQL en Render para la version publicada.
- Vite, Tailwind CSS y Alpine.js para la interfaz.
- DomPDF para reportes PDF.
- Laravel Excel para exportaciones.

## Modos de ejecucion

El proyecto queda preparado para dos entornos:

- Local: usa `.env.example`, MySQL, XAMPP y phpMyAdmin.
- Render: usa `render.yaml`, `Dockerfile` y PostgreSQL administrado por Render.

No se debe borrar `render.yaml`, `Dockerfile` ni la carpeta `docker`, porque esos archivos son necesarios para que la pagina publicada en Render siga funcionando. Para trabajar localmente no hace falta modificar esos archivos.

## Instalacion local

Antes de ejecutar Laravel, inicia Apache y MySQL desde XAMPP. Luego crea la base de datos en phpMyAdmin con el nombre:

```text
smer_tecsup
```

Tambien se puede crear por consola:

```sql
CREATE DATABASE smer_tecsup CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

En Windows, desde PowerShell o la terminal de Visual Studio Code:

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Si `php` no se reconoce como comando, usa el PHP de XAMPP:

```powershell
C:\xampp\php\php.exe artisan key:generate
C:\xampp\php\php.exe artisan migrate --seed
C:\xampp\php\php.exe artisan serve
```

Si la base de datos ya tenia datos de pruebas y se quiere reiniciar desde cero:

```powershell
php artisan migrate:fresh --seed
```

## Acceso local

La aplicacion queda disponible en:

```text
http://127.0.0.1:8000
```

Credenciales iniciales:

```text
Administrador: admin@smer.com / admin123
Tutor: carlos.mendoza@tecsup.edu.pe / tutor123
Estudiante: ana.torres@tecsup.edu.pe / estudiante123
```

Las tablas se pueden revisar desde phpMyAdmin entrando a:

```text
http://localhost/phpmyadmin
```
