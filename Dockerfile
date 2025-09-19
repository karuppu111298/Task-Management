# Base image
FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    zip \
    curl \
    && docker-php-ext-install pdo_pgsql


# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader



# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8000

# Start Laravel
# CMD php artisan serve --host=0.0.0.0 --port=8000
# Run migrations automatically on container start
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
