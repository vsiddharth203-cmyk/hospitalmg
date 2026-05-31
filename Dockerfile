FROM php:8.4-fpm

# Install system dependencies and PostgreSQL dev libraries
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nginx

# Clean up package manager cache correctly (Fixed path from /var/list to /var/lib)
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install application dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Copy our custom Nginx configuration over the default one
COPY nginx.conf /etc/nginx/sites-available/default

# Set correct permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Make sure our entrypoint script has execution permissions
RUN chmod +x /var/www/html/entrypoint.sh

EXPOSE 80

# Trigger our script on runtime deployment using explicit shell execution style
ENTRYPOINT ["/bin/sh", "/var/www/html/entrypoint.sh"]