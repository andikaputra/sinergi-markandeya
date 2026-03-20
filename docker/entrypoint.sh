#!/bin/sh
set -e

echo "Running migrations..."
php artisan migrate --force

# Seed hanya jika tabel users masih kosong (deploy pertama kali)
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "Database kosong, running seeder..."
    php artisan db:seed --force
else
    echo "Database sudah ada data, skip seeder."
fi

echo "Caching config..."
php artisan optimize

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
