FROM php:8.3-apache AS base

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN echo "UTC" > /etc/timezone

FROM base AS runner

RUN apt update && apt install -y \
    git \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    zlib1g-dev \
    && docker-php-ext-install intl opcache pdo pdo_pgsql pgsql zip \
    && apt clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

COPY .docker/apache/default.conf /etc/apache2/sites-enabled/000-default.conf

RUN composer install --optimize-autoloader --no-dev

RUN cp .env.example .env

RUN php artisan route:cache

RUN a2enmod rewrite

RUN mkdir -p /var/www/html/public/storage/logs \
    && touch /var/www/html/public/storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/html/public/storage \
    && chmod -R 775 /var/www/html/public/storage

RUN ln -sf /dev/stdout /var/www/html/public/storage/logs/laravel.log

COPY .docker/scripts/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
