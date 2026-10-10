#!/bin/sh
set -e

STEP="initialization"
trap 'status=$?; if [ "$status" -ne 0 ]; then echo "[startup] Failed during: $STEP (exit $status). Check the error above." >&2; fi' EXIT

# Storage may be a persistent volume with ownership from an older container.
STEP="storage permissions"
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Rebuild config using the environment supplied by Coolify, not a build-time cache.
STEP="clear cached configuration"
php artisan config:clear

STEP="database connection"
php docker/wait-for-database.php

STEP="database migrations"
echo "Running migrations..."
php artisan migrate --force

# Seed hanya jika tabel users masih kosong (deploy pertama kali)
STEP="check initial database"
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();")
if [ "$USER_COUNT" = "0" ]; then
    STEP="initial database seed"
    echo "Database kosong, running seeder..."
    php artisan db:seed --force
else
    echo "Database sudah ada data, skip seeder."
fi

echo "Caching config..."
STEP="cache application"
php artisan optimize

echo "Starting supervisord..."
STEP="start web services"
exec supervisord -c /etc/supervisord.conf
