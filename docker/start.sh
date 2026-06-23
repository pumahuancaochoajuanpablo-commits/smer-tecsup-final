#!/usr/bin/env bash
set -e

export PORT="${PORT:-8080}"

sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf

cat > /etc/apache2/sites-available/000-default.conf <<APACHE
<VirtualHost *:${PORT}>
    ServerName localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
APACHE

php artisan storage:link || true
php artisan config:clear
php artisan route:clear
php artisan view:clear

if [ "${APP_ENV}" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

php artisan migrate --force

if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    USER_COUNT=$(php artisan tinker --execute="echo \\App\\Models\\User::count();" 2>/dev/null || echo "0")
    if [ "${USER_COUNT}" = "0" ]; then
        php artisan db:seed --force
    else
        echo "Seeders skipped: database already has users."
    fi
fi

apache2-foreground
