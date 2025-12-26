#!/bin/bash
# Laravel Optimization Script

cd domains/magicsafaris.com/public_html

echo "Optimizing Laravel application..."

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize --no-dev 2>/dev/null || composer dump-autoload --optimize

# Clear application cache
php artisan cache:clear

# Optimize for production
php artisan optimize

echo "Laravel optimization complete!"

