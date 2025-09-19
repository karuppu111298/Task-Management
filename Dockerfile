# Dockerfile for Laravel + PostgreSQL (Apache + PHP 8.2)
FROM php:8.2-apache

# Install system deps and PHP extensions (including pgsql)
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql mbstring zip bcmath xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy composer binary from official composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy app
COPY . .

# Install composer deps (no dev)
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Ensure storage & cache perms
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Serve public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
CMD ["apache2-foreground"]
