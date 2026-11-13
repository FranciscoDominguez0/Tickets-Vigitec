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

# Esperar a que MySQL esté listo para recibir conexiones
echo ">> Comprobando conexión a la base de datos..."
MAX_TRIES=30
COUNT=0
until php -r "try { new PDO('mysql:host=' . (getenv('DB_HOST') ?: 'db') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . (getenv('DB_DATABASE') ?: 'tickets_laravel'), getenv('DB_USERNAME') ?: 'tickets_user', getenv('DB_PASSWORD') ?: '12345678'); exit(0); } catch (Exception \$e) { exit(1); }" 2>/dev/null || [ $COUNT -eq $MAX_TRIES ]; do
    echo ">> Esperando que MySQL esté listo... ($COUNT/$MAX_TRIES)"
    sleep 2
    COUNT=$((COUNT + 1))
done

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

