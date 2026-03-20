#!/bin/sh
set -e

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --force

echo "Caching config..."
php artisan optimize

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
