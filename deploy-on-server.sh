#!/bin/bash
# Server-side deployment script
# Run this on the SSH server after files are uploaded

echo "Deploying broken image fix..."
echo ""

# Find the Laravel root directory (common locations)
if [ -d "/home/u916293666/domains/meishicadi.com/public_html" ]; then
    LARAVEL_ROOT="/home/u916293666/domains/meishicadi.com/public_html"
elif [ -d "/home/u916293666/public_html" ]; then
    LARAVEL_ROOT="/home/u916293666/public_html"
elif [ -f "~/artisan" ]; then
    LARAVEL_ROOT="$(pwd)"
else
    echo "Please navigate to your Laravel root directory and run this script"
    exit 1
fi

cd $LARAVEL_ROOT || exit

echo "Laravel root: $LARAVEL_ROOT"
echo ""

# Move Handler.php
if [ -f ~/Handler.php ]; then
    echo "Moving Handler.php..."
    mv ~/Handler.php app/Exceptions/Handler.php
    chmod 644 app/Exceptions/Handler.php
fi

# Move StorageController.php
if [ -f ~/StorageController.php ]; then
    echo "Moving StorageController.php..."
    mv ~/StorageController.php app/Http/Controllers/StorageController.php
    chmod 644 app/Http/Controllers/StorageController.php
fi

# Move web.php
if [ -f ~/web.php ]; then
    echo "Moving web.php..."
    mv ~/web.php routes/web.php
    chmod 644 routes/web.php
fi

echo ""
echo "Clearing Laravel cache..."
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear

echo ""
echo "Deployment complete! The broken image fix is now active."
echo "Test by accessing: https://consulum.meishicadi.com/storage/card_logo/logo_17625296361121677974.jpg"

