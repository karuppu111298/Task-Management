FROM php:8.2-apache

# Update & install system deps
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev

# Configure GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql mbstring zip bcmath xml

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
