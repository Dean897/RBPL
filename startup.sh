#!/bin/sh
set -eu

# Ensure we are in the deployed app root.
cd /home/site/wwwroot

# Ensure Laravel writable/cache directories exist.
mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache
chmod -R 775 storage bootstrap/cache || true

# Install PHP dependencies when vendor does not exist (Zip Deploy scenario).
if [ ! -d vendor ]; then
  composer install --no-dev --optimize-autoloader --no-interaction
fi

# Point Apache docroot to Laravel public directory.
sed -ri 's!/home/site/wwwroot!/home/site/wwwroot/public!g' /etc/apache2/sites-available/000-default.conf
sed -ri 's!/home/site/wwwroot!/home/site/wwwroot/public!g' /etc/apache2/apache2.conf

# Optimize Laravel caches for production.
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start Apache in foreground.
exec apache2-foreground
