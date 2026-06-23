# Despliegue gratuito o de bajo costo para Laravel

Este proyecto es una aplicacion Laravel con base de datos. No debe desplegarse como sitio estatico en Netlify, porque Netlify no ejecuta el backend PHP de Laravel ni mantiene la base de datos de la aplicacion.

## Recomendacion

Para una demo gratuita o de pruebas, usa Render con Docker:

- Web Service para la aplicacion Laravel.
- Base de datos PostgreSQL online.
- Variables de entorno configuradas en el panel, no dentro del repositorio.

Render sirve para presentar el proyecto y probarlo, pero no debe tratarse como produccion real si se usa el plan gratuito.

## Archivos listos en este proyecto

- `Dockerfile`: construye assets, instala PHP, extensiones y Apache.
- `docker/apache.conf`: configura Apache para servir `public/`.
- `docker/start.sh`: limpia cache, ejecuta migraciones y arranca Apache.
- `render.yaml`: blueprint para crear el servicio y PostgreSQL en Render.

## Opcion A: Render Blueprint

1. Sube estos cambios a GitHub.
2. En Render, elige New > Blueprint.
3. Selecciona el repositorio.
4. Render detectara `render.yaml`.
5. Al terminar el primer despliegue, entra a Environment y cambia `RUN_SEEDERS=false` para evitar resembrar si reinicias la app.

## Opcion B: Render Web Service manual

Configura estas variables:

```env
APP_NAME="SMER Tecsup"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio-del-proveedor
APP_KEY=base64:generar-con-php-artisan-key-generate --show

DB_CONNECTION=pgsql
DB_URL=postgresql://usuario:password@host:5432/base_de_datos?sslmode=require
DB_SSLMODE=require
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
RUN_SEEDERS=true
```

## Recuperacion real de contrasena por correo

Laravel ya incluye la pantalla `Olvide mi contrasena`, pero solo enviara correos reales si configuras un proveedor SMTP en Render. Si dejas `MAIL_MAILER=log`, el enlace se genera pero queda en los logs del servidor.

Variables necesarias:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.tu-proveedor.com
MAIL_PORT=587
MAIL_USERNAME=usuario-smtp
MAIL_PASSWORD=password-smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@tu-dominio.com
MAIL_FROM_NAME="SMER Tecsup"
```

Para una demo puedes usar un SMTP gratuito o de prueba. Para produccion real, usa un correo institucional o un proveedor transaccional.

## Base de datos gratis externa

Tambien puedes crear la base en Neon o Supabase y pegar la cadena de conexion en `DB_URL`. Para este proyecto, PostgreSQL es suficiente y Laravel ya tiene soporte para `pgsql`.

## Criterio profesional

Si solo necesitas mostrar el sistema al profesor, Render gratuito o Neon/Supabase free pueden bastar. Si necesitas que la app quede estable para usuarios reales, conviene pagar un plan pequeno, porque la aplicacion requiere backend, almacenamiento y base de datos.
