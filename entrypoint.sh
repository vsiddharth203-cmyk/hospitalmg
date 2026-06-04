#!/bin/sh

echo "Checking environment and waiting to run migrations..."

# Ensure Laravel's required framework storage folders exist explicitly
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/app/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# Clear any cached configuration compiled on your local machine
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Fix application folder permissions dynamically
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run database migrations
php artisan migrate --force

echo "Database migrations completed successfully! Booting application..."

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground to keep the container alive
nginx -g "daemon off;"