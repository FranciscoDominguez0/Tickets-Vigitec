#!/bin/sh
set -e

# Crear .env desde .env.example si no existe
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Permisos de almacenamiento y caché
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache 2>/dev/null || true

# Enlace simbólico de storage público
php artisan storage:link --force 2>/dev/null || true

# Migraciones automáticas (por defecto activadas)
if [ "$RUN_MIGRATIONS" != "false" ]; then
    echo ">> Ejecutando migraciones..."
    php artisan migrate --force
fi

# Seeders si se solicitan explícitamente (ej. primer arranque del sistema)
if [ "$RUN_SEEDERS" = "true" ]; then
    echo ">> Ejecutando seeders base..."
    php artisan db:seed --force
fi

# Optimización de vistas y rutas para producción
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo ">> Iniciando Supervisord (Nginx + PHP-FPM + Queue Worker)..."
exec /usr/bin/supervisord -c /etc/supervisord.conf

