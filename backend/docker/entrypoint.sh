#!/bin/sh
set -eu

cd /var/www/html

mkdir -p \
    bootstrap/cache \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Waiting for database..."

    attempts=0

    until php artisan db:monitor >/dev/null 2>&1; do
        attempts=$((attempts + 1))

        if [ "$attempts" -ge 30 ]; then
            echo "Database did not become available."
            exit 1
        fi

        sleep 2
    done

    php artisan migrate --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"