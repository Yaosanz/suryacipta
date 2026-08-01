# syntax=docker/dockerfile:1.7

# =========================================================
# PHP base
# =========================================================
FROM php:8.4-apache-bookworm AS php-base

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        gd \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        zip \
    && a2enmod headers rewrite \
    && rm -rf /var/lib/apt/lists/*

# =========================================================
# Composer dependencies
# =========================================================
FROM php-base AS vendor

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --classmap-authoritative \
    --no-ansi \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist

COPY . .

RUN composer dump-autoload \
    --classmap-authoritative \
    --no-ansi \
    --no-dev \
    --no-interaction \
    --no-scripts \
    && APP_ENV=production \
       APP_DEBUG=false \
       LOG_CHANNEL=stderr \
       php artisan package:discover --ansi


# =========================================================
# Laravel/Vite assets
# =========================================================
FROM node:24-alpine AS assets

WORKDIR /app

# Harus tersedia di folder backend dan masuk ke repository Git.
COPY package.json package-lock.json ./

RUN npm ci \
    --ignore-scripts \
    --no-audit \
    --no-fund

# Salin source setelah dependency agar Docker cache lebih efektif.
COPY --from=vendor /app /app

RUN npm run build


# =========================================================
# Runtime
# =========================================================
FROM php-base AS runtime

WORKDIR /var/www/html

ENV APACHE_LOCK_DIR=/tmp/apache2-lock \
    APACHE_LOG_DIR=/tmp/apache2-log \
    APACHE_PID_FILE=/tmp/apache2-run/apache2.pid \
    APACHE_RUN_DIR=/tmp/apache2-run \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr

RUN a2dissite 000-default \
    && rm -f /etc/apache2/sites-available/000-default.conf

COPY docker/apache-vhost.conf \
    /etc/apache2/sites-available/suryacipta.conf

COPY docker/apache-hardening.conf \
    /etc/apache2/conf-available/suryacipta-hardening.conf

COPY docker/ports.conf \
    /etc/apache2/ports.conf

COPY docker/php-production.ini \
    /usr/local/etc/php/conf.d/99-production.ini

RUN a2ensite suryacipta \
    && a2enconf suryacipta-hardening

COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

COPY docker/entrypoint.sh \
    /usr/local/bin/suryacipta-entrypoint

RUN mkdir -p \
        /tmp/apache2-lock \
        /tmp/apache2-log \
        /tmp/apache2-run \
        bootstrap/cache \
        storage/app/private/inquiries \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
    && chown -R www-data:www-data \
        /tmp/apache2-lock \
        /tmp/apache2-log \
        /tmp/apache2-run \
        bootstrap/cache \
        storage \
    && chmod -R u=rwX,g=rX,o= \
        bootstrap/cache \
        storage \
    && ln -sfn ../storage/app/public public/storage \
    && chmod 0755 /usr/local/bin/suryacipta-entrypoint

USER www-data

EXPOSE 8080

HEALTHCHECK --interval=30s \
    --timeout=5s \
    --start-period=30s \
    --retries=3 \
    CMD ["php", "-r", "$s=@fsockopen('127.0.0.1',8080,$e,$m,2);if(!$s)exit(1);fwrite($s,\"GET /up HTTP/1.0\\r\\nHost: 127.0.0.1\\r\\n\\r\\n\");$line=fgets($s);fclose($s);exit(str_contains($line,' 200 ')?0:1);"]

ENTRYPOINT ["/usr/local/bin/suryacipta-entrypoint"]
CMD ["apache2-foreground"]