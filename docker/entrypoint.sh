#!/bin/sh
set -e

# Clear cache if any exists from host
if [ -d "/var/www/storage/framework/views" ]; then
    find /var/www/storage/framework/views -type f -name '*.php' -delete
fi

# Ensure directories exist
mkdir -p /var/www/storage/framework/sessions /var/www/storage/framework/views /var/www/storage/framework/cache /var/www/storage/logs
mkdir -p /var/www/bootstrap/cache

# Setup permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Create storage link
php artisan storage:link --ansi --no-interaction || true

# Run migrations in production
if [ "$APP_ENV" = "production" ]; then
    echo "Running migrations..."
    php artisan migrate --force --ansi
fi

# Execution
exec "$@"
