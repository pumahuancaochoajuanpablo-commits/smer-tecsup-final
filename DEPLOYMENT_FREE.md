# Despliegue gratuito o de bajo costo para Laravel

Este proyecto es una aplicacion Laravel con base de datos. No debe desplegarse como sitio estatico en Netlify, porque Netlify no ejecuta el backend PHP de Laravel ni mantiene la base de datos de la aplicacion.

## Recomendacion

Para una demo gratuita o de pruebas, usa Render con Docker:

- Web Service para la aplicacion Laravel.
- Base de datos MySQL externa online.
- Variables de entorno configuradas en el panel, no dentro del repositorio.

Render sirve para presentar el proyecto y probarlo, pero no debe tratarse como produccion real si se usa el plan gratuito. Render no crea MySQL administrado desde `render.yaml`; por eso debes usar un proveedor MySQL externo y pegar la conexion en Environment.

## Archivos listos en este proyecto

- `Dockerfile`: construye assets, instala PHP, extensiones y Apache.
- `docker/apache.conf`: configura Apache para servir `public/`.
- `docker/start.sh`: limpia cache, ejecuta migraciones y arranca Apache.
- `render.yaml`: blueprint para crear el servicio web en Render usando una base MySQL externa.

## Opcion A: Render Blueprint

1. Sube estos cambios a GitHub.
2. En Render, elige New > Blueprint.
3. Selecciona el repositorio.
4. Render detectara `render.yaml`.
5. Antes de aplicar el despliegue, agrega manualmente `DB_URL` con la conexion MySQL externa.
6. Al terminar el primer despliegue, entra a Environment y cambia `RUN_SEEDERS=false` para evitar resembrar si reinicias la app.

## Opcion B: Render Web Service manual

Configura estas variables:

```env
APP_NAME="SMER Tecsup"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio-del-proveedor
APP_KEY=base64:generar-con-php-artisan-key-generate --show

DB_CONNECTION=mysql
DB_URL=mysql://usuario:password@host:3306/base_de_datos
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
RUN_SEEDERS=true
```

Para MySQL tambien puedes separar la conexion en variables:

```env
DB_CONNECTION=mysql
DB_HOST=host-mysql-online
DB_PORT=3306
DB_DATABASE=smer_tecsup
DB_USERNAME=usuario_mysql
DB_PASSWORD=password_mysql
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

## Recuperacion real de contrasena por correo en Render Free

Laravel ya incluye la pantalla `Olvide mi contrasena`. En Render Free no conviene usar SMTP porque Render bloquea la salida a los puertos SMTP 25, 465 y 587. Por eso el proyecto envia los enlaces de recuperacion usando la API HTTPS de Brevo.

Variables necesarias:

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=correo-verificado-en-brevo@dominio.com
MAIL_FROM_NAME="SMER Tecsup"
BREVO_API_KEY=tu_api_key_de_brevo
```

El `MAIL_FROM_ADDRESS` debe estar verificado como remitente en Brevo. Si se usa un plan pago de Render, tambien se puede volver a SMTP, pero para el plan gratuito la opcion estable es API.

## Base de datos MySQL externa

Para usar MySQL gratis o de bajo costo, crea una base MySQL en un proveedor externo y copia la cadena de conexion. En Render, agrega esa cadena como `DB_URL`.

Ejemplo:

```env
DB_CONNECTION=mysql
DB_URL=mysql://usuario:password@host:3306/smer_tecsup
```

Si el proveedor exige SSL, agrega tambien el certificado o las variables que indique. No subas usuarios, passwords ni certificados al repositorio.

## Criterio profesional

Si solo necesitas mostrar el sistema al profesor, Render gratuito con MySQL externo puede bastar. Si necesitas que la app quede estable para usuarios reales, conviene pagar un plan pequeno, porque la aplicacion requiere backend, almacenamiento y base de datos.
