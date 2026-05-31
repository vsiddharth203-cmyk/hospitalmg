FROM php:8.2-fpm

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

# Clear cache
RUN apt-get clean && rm -rf /var/list/apt/lists/*

# Install PHP extensions (including pdo_pgsql and pgsql)
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install application dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set correct permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx configuration (If you have a custom one, otherwise use standard)
EXPOSE 80

# Run entrypoint script or start nginx/php-fpm directly
CMD service nginx start && php-fpm