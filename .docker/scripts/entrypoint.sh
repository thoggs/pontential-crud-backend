#!/bin/sh

# Exit immediately if a command exits with a non-zero status
set -e

# Wait for dependencies to be ready
sleep 10s

# Ensure all necessary storage directories exist and have the correct permissions
mkdir -p /var/www/html/storage/framework/views /var/www/html/public/storage/logs
touch /var/www/html/public/storage/logs/laravel.log
chown -R www-data:www-data /var/www/html/storage /var/www/html/public/storage
chmod -R 775 /var/www/html/storage /var/www/html/public/storage

# Debugging: List storage directory
ls -la /var/www/html/storage
ls -la /var/www/html/storage/framework/views
ls -la /var/www/html/public/storage
ls -la /var/www/html/public/storage/logs

# Run Laravel migrations and seeders
php artisan migrate
php artisan db:seed --class=DeveloperModelSeeder

# Start Apache server
exec apache2-foreground
