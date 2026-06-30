# Guia interna de estructura del proyecto SMER Tecsup

Este documento explica como esta compuesto el proyecto, que responsabilidad tiene cada carpeta y para que sirve cada archivo principal. La finalidad es que puedas defender el codigo, ubicar errores y modificar el sistema localmente con XAMPP y MySQL.

## 1. Vision general

El proyecto es una aplicacion web desarrollada con Laravel 12 y PHP 8.2. Usa el patron MVC:

- Modelo: clases dentro de `app/Models`, representan tablas de la base de datos.
- Vista: archivos Blade dentro de `resources/views`, representan pantallas HTML.
- Controlador: clases dentro de `app/Http/Controllers`, reciben la peticion, consultan modelos y devuelven vistas o archivos.

La base de datos local esperada es MySQL, administrada desde XAMPP/phpMyAdmin:

- Host: `127.0.0.1`
- Puerto: `3306`
- Base de datos: `smer_tecsup`
- Usuario: `root`
- Contrasena: vacia por defecto en XAMPP

## 2. Carpetas principales

### `app`

Contiene el codigo principal del backend. Aqui vive la logica de negocio, los modelos, controladores, middleware, servicios y componentes de vista.

### `bootstrap`

Contiene el arranque de Laravel. Define como se inicia la aplicacion, que proveedores se cargan y donde se guarda cache de configuracion.

### `config`

Contiene archivos de configuracion de Laravel. Aqui se define la conexion a base de datos, correo, sesiones, colas, logs, cache y servicios externos.

### `database`

Contiene migraciones, seeders y factories. Es la carpeta que define la estructura inicial de la base de datos y la data de prueba.

### `docs`

Contiene documentacion interna del proyecto. Este archivo pertenece a esa carpeta.

### `lang`

Contiene traducciones al espanol para mensajes de validacion, autenticacion y recuperacion de contrasena.

### `plantillas`

Contiene archivos de ejemplo para importar estudiantes desde CSV o Excel.

### `public`

Es la carpeta publica que atiende el navegador. Contiene `index.php`, imagenes publicas y archivos accesibles directamente desde la web.

### `resources`

Contiene vistas Blade, CSS y JavaScript fuente. Es la parte visible del sistema antes de compilar los assets.

### `routes`

Contiene las rutas web, rutas de autenticacion y comandos de consola. Las rutas conectan URLs con controladores.

### `storage`

Contiene archivos generados por la aplicacion: sesiones, cache, vistas compiladas, archivos privados, archivos publicos y logs. En Git solo se guardan archivos `.gitignore` para mantener la estructura.

### `tests`

Contiene pruebas automatizadas para validar autenticacion, perfil, derivaciones y comportamiento base del sistema.

### `vendor`

Contiene dependencias PHP instaladas por Composer. No se modifica manualmente.

### `node_modules`

Contiene dependencias frontend instaladas por npm. No se modifica manualmente.

## 3. Archivos de la raiz

### `.editorconfig`

Define reglas basicas de formato para editores: espacios, saltos de linea y codificacion.

### `.env`

Archivo local de configuracion real. Contiene variables como `APP_KEY`, conexion MySQL, correo y modo de ejecucion. No debe subirse al repositorio.

### `.env.example`

Plantilla segura para crear un `.env` nuevo. En este proyecto queda preparada para MySQL local con XAMPP.

### `.gitattributes`

Reglas de Git para tratar archivos de texto y exportaciones.

### `.gitignore`

Lista archivos y carpetas que Git no debe versionar, como `.env`, `vendor`, `node_modules`, logs y cache.

### `artisan`

Ejecutable de consola de Laravel. Sirve para correr comandos como migraciones, seeders, servidor local, cache y pruebas.

### `composer.json`

Declara dependencias PHP del proyecto. Las principales son Laravel 12, DomPDF para PDF y Laravel Excel para exportar/importar hojas.

### `composer.lock`

Guarda versiones exactas de dependencias PHP instaladas. Permite que otro equipo instale las mismas versiones.

### `package.json`

Declara dependencias y scripts frontend. Usa Vite, Tailwind y Axios.

### `package-lock.json`

Guarda versiones exactas de dependencias npm.

### `phpunit.xml`

Configura las pruebas automatizadas. Para pruebas usa SQLite en memoria, asi no borra ni modifica la base MySQL local.

### `postcss.config.js`

Configura PostCSS para procesar Tailwind.

### `README.md`

Guia corta para instalar y correr el proyecto localmente.

### `tailwind.config.js`

Configura Tailwind CSS y le indica donde buscar clases dentro de vistas y JavaScript.

### `vite.config.js`

Configura Vite para compilar `resources/css/app.css` y `resources/js/app.js`.

## 4. Carpeta `app`

### `app/Exports/AuditLogsExport.php`

Exporta registros de auditoria a Excel usando Laravel Excel.

### `app/Exports/EntrevistasExport.php`

Exporta entrevistas registradas a Excel.

### `app/Http/Controllers/Controller.php`

Controlador base de Laravel. Los controladores del proyecto heredan el comportamiento general del framework.

### `app/Http/Controllers/AdminController.php`

Controlador principal del administrador. Maneja panel administrativo, tutores, importacion de estudiantes, asignaciones, encuestas, configuracion, reportes PDF y exportaciones.

### `app/Http/Controllers/AuditController.php`

Controla la vista de auditoria, detalle de registros y exportacion de logs.

### `app/Http/Controllers/DerivacionController.php`

Controla derivaciones de estudiantes en riesgo. Permite crear, listar, ver, actualizar estado y enviar notificaciones asociadas a derivacion.

### `app/Http/Controllers/EstudianteController.php`

Controla las vistas del estudiante: estado actual y notificaciones.

### `app/Http/Controllers/ProfileController.php`

Controla edicion y eliminacion del perfil del usuario autenticado.

### `app/Http/Controllers/TutorController.php`

Controla el panel del tutor, estudiantes asignados, registro de entrevistas, historial, observaciones, alertas y reportes individuales.

### `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

Controla inicio y cierre de sesion.

### `app/Http/Controllers/Auth/ConfirmablePasswordController.php`

Controla confirmacion de contrasena para acciones sensibles.

### `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`

Controla reenvio de verificacion de correo.

### `app/Http/Controllers/Auth/EmailVerificationPromptController.php`

Muestra pantalla de verificacion de correo.

### `app/Http/Controllers/Auth/NewPasswordController.php`

Procesa el restablecimiento de contrasena cuando el usuario llega desde un enlace valido.

### `app/Http/Controllers/Auth/PasswordController.php`

Permite cambiar contrasena desde el perfil.

### `app/Http/Controllers/Auth/PasswordResetLinkController.php`

Recibe el correo del usuario y solicita el envio del enlace de recuperacion de contrasena.

### `app/Http/Controllers/Auth/RegisteredUserController.php`

Controla registro de usuarios si la ruta se usa. En este sistema el flujo principal lo administra el administrador.

### `app/Http/Controllers/Auth/VerifyEmailController.php`

Procesa verificacion de email.

### `app/Http/Middleware/CheckRole.php`

Middleware personalizado que protege rutas por rol. Evita que un usuario entre a pantallas que no le corresponden.

### `app/Http/Requests/Auth/LoginRequest.php`

Valida datos de login y maneja intentos de autenticacion.

### `app/Http/Requests/ProfileUpdateRequest.php`

Valida cambios del perfil del usuario.

### `app/Models/Role.php`

Modelo de roles. Representa perfiles como administrador, tutor y estudiante.

### `app/Models/User.php`

Modelo de usuarios del sistema. Maneja autenticacion, rol, password con hash y relacion con tutor o estudiante.

### `app/Models/Tutor.php`

Modelo de tutores. Relaciona datos del tutor con su usuario y sus asignaciones.

### `app/Models/Estudiante.php`

Modelo de estudiantes. Guarda codigo, carrera, ciclo, grupo, edad, estado y relacion con usuario, asignaciones y notificaciones.

### `app/Models/Asignacion.php`

Modelo que une tutor y estudiante. Permite saber que tutor atiende a que estudiante y desde que fecha.

### `app/Models/Entrevista.php`

Modelo de entrevistas o encuestas. Guarda los seis indicadores, puntaje total, nivel de riesgo y documento asociado.

### `app/Models/ParametroRiesgo.php`

Modelo para configurar umbrales o parametros usados en el calculo de riesgo.

### `app/Models/Recomendacion.php`

Modelo que guarda recomendaciones segun el nivel de riesgo.

### `app/Models/Observacion.php`

Modelo para observaciones del tutor asociadas a entrevistas.

### `app/Models/Notificacion.php`

Modelo de notificaciones para estudiantes o usuarios.

### `app/Models/Derivacion.php`

Modelo de derivaciones. Registra casos derivados, estado, prioridad y datos del estudiante.

### `app/Models/AuditLog.php`

Modelo de auditoria. Registra acciones importantes del sistema.

### `app/Providers/AppServiceProvider.php`

Proveedor general de Laravel. Sirve para configurar comportamiento global al iniciar la aplicacion.

### `app/Services/BrevoEmailService.php`

Servicio para enviar correos mediante Brevo cuando esta configurado.

### `app/Services/BrevoPasswordResetMailer.php`

Servicio especifico para enviar enlaces de recuperacion de contrasena.

### `app/Services/EntrevistaService.php`

Servicio que concentra calculo y reglas relacionadas con entrevistas.

### `app/Services/ReporteService.php`

Servicio para construir datos de reportes PDF o reportes generales.

### `app/View/Components/AppLayout.php`

Componente de layout para vistas internas autenticadas.

### `app/View/Components/GuestLayout.php`

Componente de layout para vistas publicas o de autenticacion.

## 5. Carpeta `bootstrap`

### `bootstrap/app.php`

Configura la aplicacion, rutas, middleware y manejo de excepciones.

### `bootstrap/providers.php`

Lista proveedores que Laravel carga al iniciar.

### `bootstrap/cache/.gitignore`

Mantiene la carpeta de cache en Git sin guardar archivos generados.

## 6. Carpeta `config`

### `config/app.php`

Configuracion general: nombre, entorno, zona horaria, idioma y clave de aplicacion.

### `config/auth.php`

Configuracion de autenticacion, guards, usuarios y recuperacion de contrasena.

### `config/cache.php`

Configuracion de cache.

### `config/database.php`

Configuracion de base de datos. En este proyecto queda orientado a MySQL local.

### `config/filesystems.php`

Configuracion de discos de archivos: local, publico y privados.

### `config/logging.php`

Configuracion de logs del sistema.

### `config/mail.php`

Configuracion de correo. Puede usar SMTP si se configura.

### `config/queue.php`

Configuracion de colas. Permite ejecutar trabajos en segundo plano si se activa.

### `config/services.php`

Configuracion de servicios externos, como Brevo.

### `config/session.php`

Configuracion de sesiones de usuario.

## 7. Carpeta `database`

### `database/.gitignore`

Evita subir archivos de base de datos locales no necesarios.

### `database/factories/UserFactory.php`

Factory para crear usuarios ficticios en pruebas o desarrollo. En el proyecto existe, pero el poblamiento principal se hace con seeders.

### Migraciones

Las migraciones crean y modifican tablas. Son la version controlada de la base de datos.

- `0000_01_01_000000_create_roles_table.php`: crea tabla de roles.
- `0001_01_01_000000_create_users_table.php`: crea usuarios y datos de autenticacion.
- `0001_01_01_000001_create_cache_table.php`: crea tablas para cache.
- `0001_01_01_000002_create_jobs_table.php`: crea tablas para trabajos en cola.
- `2024_01_01_000003_create_tutores_table.php`: crea tutores.
- `2024_01_01_000004_create_estudiantes_table.php`: crea estudiantes.
- `2024_01_01_000005_create_asignaciones_table.php`: crea asignaciones tutor-estudiante.
- `2024_01_01_000006_create_parametros_riesgo_table.php`: crea parametros de riesgo.
- `2024_01_01_000007_create_entrevistas_table.php`: crea entrevistas.
- `2024_01_01_000008_create_recomendaciones_table.php`: crea recomendaciones.
- `2024_01_01_000009_create_observaciones_table.php`: crea observaciones.
- `2024_01_01_000010_create_notificaciones_table.php`: crea notificaciones.
- `2024_01_01_000011_add_fields_to_tables.php`: agrega campos adicionales a tablas existentes.
- `2024_01_01_000012_create_audit_logs_table.php`: crea auditoria.
- `2024_01_01_000013_add_documento_to_entrevistas_table.php`: agrega documento a entrevistas.
- `2026_06_18_140853_create_derivaciones_table.php`: crea derivaciones.
- `2026_06_22_190000_add_profile_fields_to_estudiantes_table.php`: agrega datos de perfil de estudiante.
- `2026_06_23_180000_ensure_bienestar_admin_user.php`: asegura usuario administrativo de bienestar.

### Seeders

Los seeders insertan datos iniciales para que el sistema funcione sin cargar todo manualmente.

- `DatabaseSeeder.php`: ejecuta los seeders principales en orden.
- `RoleSeeder.php`: crea roles base.
- `UserSeeder.php`: crea usuarios base.
- `TutorSeeder.php`: crea tutores.
- `EstudianteSeeder.php`: crea estudiantes de prueba.
- `AsignacionSeeder.php`: asigna estudiantes a tutores.
- `EntrevistaSeeder.php`: crea entrevistas iniciales.
- `ParametroRiesgoSeeder.php`: crea parametros de riesgo.
- `RecomendacionSeeder.php`: crea recomendaciones por nivel.
- `ActualizarCarrerasSeeder.php`: actualiza carreras de estudiantes existentes.

## 8. Carpeta `lang`

### `lang/es/auth.php`

Mensajes en espanol para autenticacion.

### `lang/es/passwords.php`

Mensajes en espanol para recuperacion de contrasena.

### `lang/es/validation.php`

Mensajes en espanol para validaciones de formularios.

## 9. Carpeta `plantillas`

### `plantillas/plantilla_importar_estudiantes.csv`

Plantilla CSV para importar estudiantes.

### `plantillas/plantilla_importar_estudiantes.xlsx`

Plantilla Excel para importar estudiantes.

## 10. Carpeta `public`

### `public/.htaccess`

Configuracion para servidores Apache.

### `public/favicon.ico`

Icono del sitio.

### `public/index.php`

Punto de entrada web de Laravel. Todas las solicitudes pasan por este archivo.

### `public/logo-tecsup.png`

Logo usado en la interfaz.

### `public/robots.txt`

Archivo basico para rastreadores web.

## 11. Carpeta `resources`

### `resources/css/app.css`

CSS principal del sistema. Contiene estilos globales y ajustes visuales.

### `resources/js/app.js`

Entrada JavaScript principal para Vite.

### `resources/js/bootstrap.js`

Configura Axios y comportamiento base de JavaScript.

## 12. Vistas Blade

### `resources/views/layouts/app.blade.php`

Layout principal para pantallas internas autenticadas.

### `resources/views/layouts/guest.blade.php`

Layout para pantallas de login, registro y recuperacion de contrasena.

### Vistas de autenticacion

- `auth/login.blade.php`: formulario de inicio de sesion.
- `auth/register.blade.php`: formulario de registro.
- `auth/forgot-password.blade.php`: solicitud de enlace de recuperacion.
- `auth/reset-password.blade.php`: cambio de contrasena con token.
- `auth/confirm-password.blade.php`: confirmacion de contrasena.
- `auth/verify-email.blade.php`: verificacion de correo.

### Vistas del administrador

- `admin/dashboard-new.blade.php`: panel principal administrativo.
- `admin/tutores/index.blade.php`: registro y listado de tutores.
- `admin/estudiantes/importar.blade.php`: importacion de estudiantes.
- `admin/asignaciones/index.blade.php`: asignacion de tutores a estudiantes.
- `admin/configuracion/index.blade.php`: configuracion de parametros del sistema.
- `admin/encuestas/index.blade.php`: listado de encuestas.
- `admin/encuestas/show.blade.php`: detalle de una encuesta.
- `admin/auditoria/index.blade.php`: listado de auditoria.
- `admin/auditoria/show.blade.php`: detalle de auditoria.

### Vistas del tutor

- `tutor/dashboard.blade.php`: panel principal del tutor.
- `tutor/estudiantes.blade.php`: estudiantes asignados al tutor.
- `tutor/entrevista/create.blade.php`: formulario de entrevista.
- `tutor/historial.blade.php`: historial de entrevistas.
- `tutor/observaciones.blade.php`: observaciones del tutor.
- `tutor/alertas.blade.php`: alertas de estudiantes en riesgo.

### Vistas del estudiante

- `estudiante/estado.blade.php`: estado de riesgo del estudiante.
- `estudiante/notificaciones.blade.php`: notificaciones del estudiante.

### Vistas de derivaciones

- `derivaciones/index.blade.php`: listado de derivaciones.
- `derivaciones/crear.blade.php`: formulario para derivar estudiante.
- `derivaciones/ver.blade.php`: detalle de derivacion.
- `derivaciones/estadisticas.blade.php`: estadisticas de derivaciones.

### Vistas de reportes

- `reportes/ficha-individual.blade.php`: plantilla PDF individual.
- `reportes/informe-general.blade.php`: plantilla PDF general.

### Vistas de emails

- `emails/password-reset.blade.php`: correo de recuperacion de contrasena.
- `emails/derivacion-alerta.blade.php`: correo de alerta por derivacion.

### Vistas de errores

- `errors/layout.blade.php`: layout base de paginas de error.
- `errors/403.blade.php`: acceso denegado.
- `errors/404.blade.php`: pagina no encontrada.
- `errors/419.blade.php`: sesion expirada.
- `errors/429.blade.php`: demasiadas solicitudes.
- `errors/500.blade.php`: error interno.
- `errors/503.blade.php`: servicio no disponible.

### Componentes Blade

- `components/auth-session-status.blade.php`: muestra mensajes de sesion en autenticacion.
- `components/danger-button.blade.php`: boton de accion peligrosa.
- `components/input-error.blade.php`: muestra errores de validacion.
- `components/input-label.blade.php`: etiqueta de formulario.
- `components/modal.blade.php`: modal reutilizable.
- `components/primary-button.blade.php`: boton principal.
- `components/secondary-button.blade.php`: boton secundario.
- `components/sidebar-link.blade.php`: enlace lateral del menu.
- `components/text-input.blade.php`: campo de texto reutilizable.

### Perfil

- `profile/edit.blade.php`: pantalla de perfil.
- `profile/partials/update-profile-information-form.blade.php`: formulario de informacion personal.
- `profile/partials/update-password-form.blade.php`: formulario de cambio de contrasena.
- `profile/partials/delete-user-form.blade.php`: formulario de eliminacion de usuario.

## 13. Carpeta `routes`

### `routes/web.php`

Define rutas principales del sistema. Separa rutas por rol: administrador, tutor y estudiante.

### `routes/auth.php`

Define rutas de login, logout, registro, recuperacion y verificacion de correo.

### `routes/console.php`

Define comandos de consola propios si el proyecto los necesita.

## 14. Carpeta `storage`

### `storage/app/.gitignore`

Mantiene la carpeta de archivos de aplicacion sin subir archivos generados.

### `storage/app/private/.gitignore`

Mantiene estructura para archivos privados.

### `storage/app/public/.gitignore`

Mantiene estructura para archivos publicos generados por la aplicacion.

### `storage/framework/cache/.gitignore`

Mantiene estructura de cache.

### `storage/framework/cache/data/.gitignore`

Mantiene estructura de datos de cache.

### `storage/framework/sessions/.gitignore`

Mantiene estructura para sesiones.

### `storage/framework/testing/.gitignore`

Mantiene estructura de archivos temporales de pruebas.

### `storage/framework/views/.gitignore`

Mantiene estructura de vistas compiladas.

## 15. Carpeta `tests`

### `tests/TestCase.php`

Clase base de pruebas.

### `tests/Unit/ExampleTest.php`

Prueba unitaria de ejemplo.

### `tests/Feature/ExampleTest.php`

Prueba funcional base.

### `tests/Feature/ProfileTest.php`

Valida funcionalidades de perfil.

### `tests/Feature/DerivacionTest.php`

Valida flujo de derivacion y evita regresiones en ese modulo.

### Pruebas de autenticacion

- `tests/Feature/Auth/AuthenticationTest.php`: login y logout.
- `tests/Feature/Auth/RegistrationTest.php`: registro.
- `tests/Feature/Auth/PasswordResetTest.php`: recuperacion de contrasena.
- `tests/Feature/Auth/PasswordUpdateTest.php`: cambio de contrasena.
- `tests/Feature/Auth/PasswordConfirmationTest.php`: confirmacion de contrasena.
- `tests/Feature/Auth/EmailVerificationTest.php`: verificacion de correo.

## 16. Relaciones principales del sistema

- Un `Role` tiene muchos `User`.
- Un `User` pertenece a un `Role`.
- Un `User` puede tener un `Tutor`.
- Un `User` puede tener un `Estudiante`.
- Un `Tutor` tiene muchas `Asignacion`.
- Un `Estudiante` tiene muchas `Asignacion`.
- Una `Asignacion` pertenece a un `Tutor` y a un `Estudiante`.
- Una `Asignacion` tiene muchas `Entrevista`.
- Una `Entrevista` pertenece a una `Asignacion`.
- Una `Entrevista` puede tener muchas `Observacion`.
- Una `Entrevista` tiene una recomendacion segun su nivel de riesgo.
- Un `Estudiante` puede tener muchas `Notificacion`.
- Una `Derivacion` registra un caso asociado a un estudiante y a una entrevista o asignacion segun el flujo.

## 17. Flujo general del sistema

1. El administrador crea tutores, importa estudiantes y asigna estudiantes a tutores.
2. El tutor ingresa al sistema y ve sus estudiantes asignados.
3. El tutor registra entrevistas con seis indicadores de riesgo.
4. El sistema calcula puntaje total y nivel de riesgo.
5. El tutor puede agregar observaciones y derivar casos de riesgo.
6. El administrador revisa encuestas, reportes, auditoria y configuraciones.
7. El estudiante puede ver su estado y notificaciones si tiene usuario habilitado.

## 18. Limpieza realizada

Se retiraron archivos que no correspondian a la version local con XAMPP/MySQL o que eran plantillas sin uso:

- Archivos de despliegue externo que no corresponden a la version local.
- Vistas antiguas de dashboard que ya no eran llamadas.
- Componentes Blade de navegacion generados por plantilla que no estaban en uso.
- Vista `welcome` antigua que no se usaba porque la ruta principal redirige al login.

## 19. Que no tiene el proyecto actualmente

- No depende de servicios externos de despliegue para funcionar localmente.
- No tiene triggers SQL implementados.
- No tiene procedimientos almacenados implementados.
- No tiene views SQL implementadas.
- Si tiene migraciones.
- Si tiene seeders.
- Si tiene una factory de usuario.
- Si tiene relaciones Eloquent entre modelos.

## 20. Comandos utiles para trabajar localmente

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Para verificar:

```bash
php artisan test
npm run build
php artisan route:list
```

Para entrar a la base de datos desde navegador:

```text
http://localhost/phpmyadmin
```

Base de datos:

```text
smer_tecsup
```
