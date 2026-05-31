#!/bin/sh

echo "Checking environment and waiting to run migrations..."

# Run database migrations forcefully
php artisan migrate --force

echo "Database migrations completed successfully! Booting application..."

# Start PHP-FPM in the background and Nginx in the foreground
php-fpm -D && nginx -g "daemon off;"