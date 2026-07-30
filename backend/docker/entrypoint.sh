#!/bin/sh
set -eu

umask 027

mkdir -p \
  /tmp/apache2-lock \
  /tmp/apache2-log \
  /tmp/apache2-run \
  bootstrap/cache \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views

php artisan optimize:clear

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force
fi

php artisan optimize

exec "$@"
