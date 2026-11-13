###############################################################
# Sistema de Tickets Laravel — Dockerfile Multi-Stage
# PHP 8.4-FPM + Nginx interno | Composer 2 | Node 22
###############################################################

#############################
# Stage 1: Composer (Vendor)
#############################
FROM php:8.4-cli-alpine AS vendor

RUN apk add --no-cache git unzip libzip icu-libs libpng libjpeg-turbo freetype libwebp \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS libzip-dev icu-dev \
        libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd pdo_mysql zip intl \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --no-progress --prefer-dist

#############################
# Stage 2: Vite (Frontend Assets)
#############################
FROM node:22-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts
COPY . .
RUN npm run build

#############################
# Stage 3: Runtime (Nginx + PHP-FPM + Supervisor)
#############################
FROM php:8.4-fpm-alpine AS runtime

WORKDIR /var/www/html

RUN apk add --no-cache nginx supervisor mysql-client libzip icu-libs libpng libjpeg-turbo freetype libwebp \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS libzip-dev icu-dev \
        libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath exif gd intl opcache pcntl pdo_mysql zip \
    && apk del .build-deps \
    && rm -rf /tmp/pear \
    && mkdir -p /run/nginx /run/php-fpm

# Copiar configuraciones personalizadas
COPY docker/php/php.ini        /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/www.conf       /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf   /etc/supervisord.conf
COPY docker/php/entrypoint.sh  /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copiar dependencias de Composer y código fuente
COPY --from=vendor /app/vendor ./vendor
COPY . .
# Copiar assets compilados de Vite
COPY --from=frontend /app/public/build ./public/build

# Permisos para el usuario de Nginx / PHP-FPM
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

