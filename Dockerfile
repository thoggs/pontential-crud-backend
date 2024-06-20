FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Set environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set timezone
RUN echo "UTC" > /etc/timezone

# Install dependencies
RUN apt update && apt install -y \
    git \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    zlib1g-dev \
    && docker-php-ext-install intl opcache pdo pdo_pgsql pgsql zip

# Install Node.js and NPM
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
RUN apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Copy Apache configuration file
COPY .docker/apache/default.conf /etc/apache2/sites-enabled/000-default.conf

# Install dependencies with Composer
RUN composer install --optimize-autoloader --no-dev

# Copy configuration files
RUN cp .env.example .env

# Cache routes
RUN php artisan route:cache

# Enable Apache modules
RUN a2enmod rewrite

# Set up permissions for storage and cache directories
RUN mkdir -p /var/www/html/public/storage/logs \
    && touch /var/www/html/public/storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/html/public/storage \
    && chmod -R 775 /var/www/html/public/storage

# Link Laravel log file to stdout
RUN ln -sf /dev/stdout /var/www/html/public/storage/logs/laravel.log

# Copy entrypoint script and make it executable
COPY .docker/scripts/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80
EXPOSE 80

# Set the entrypoint to execute the script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
