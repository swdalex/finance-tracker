FROM php:8.3-fpm

# Install dependencies
RUN apt-get update \
    && apt-get install -y libpq-dev unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 9000

CMD ["php-fpm"]
