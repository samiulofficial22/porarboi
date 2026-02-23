#!/bin/sh
set -e

# Ensure storage and bootstrap/cache are writable
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Create storage link if not exists
php artisan storage:link --ansi --no-interaction || true

# Cache configuration and routes for performance
if [ "$NODE_ENV" = "production" ] || [ "$APP_ENV" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Run migrations if database is ready (optional, but often needed)
# php artisan migrate --force

exec "$@"
