#!/bin/bash

# Meishicadi Deployment Script
# Run this script on your SSH server after uploading the production archive

echo "🚀 Starting Meishicadi deployment..."

# Set variables
PROJECT_NAME="meishicadi"
WEB_DIR="/var/www/html"
BACKUP_DIR="/var/backups/meishicadi"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Backup current installation if it exists
if [ -d "$WEB_DIR/$PROJECT_NAME" ]; then
    echo "📦 Creating backup of current installation..."
    tar -czf "$BACKUP_DIR/backup_$TIMESTAMP.tar.gz" -C "$WEB_DIR" "$PROJECT_NAME"
    echo "✅ Backup created: backup_$TIMESTAMP.tar.gz"
fi

# Extract the production archive
echo "📂 Extracting production archive..."
cd $WEB_DIR
tar -xzf "$PROJECT_NAME-production.tar.gz"

# Set proper ownership and permissions
echo "🔐 Setting proper permissions..."
chown -R www-data:www-data "$WEB_DIR/$PROJECT_NAME"
chmod -R 755 "$WEB_DIR/$PROJECT_NAME/storage"
chmod -R 755 "$WEB_DIR/$PROJECT_NAME/bootstrap/cache"
chmod 644 "$WEB_DIR/$PROJECT_NAME/.env"

# Navigate to project directory
cd "$WEB_DIR/$PROJECT_NAME"

# Install production dependencies
echo "📦 Installing production dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Clear and cache configurations
echo "🗑️ Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Cache configurations for production
echo "⚡ Caching configurations..."
php artisan config:cache
php artisan view:cache
php artisan route:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Optimize for production
echo "🚀 Optimizing for production..."
php artisan optimize

echo "✅ Deployment completed successfully!"
echo "🌐 Your Meishicadi application is now live at: https://meishicadi.com"
echo "📧 Admin login: info@dodotech.tech"
echo "🔑 Check the README_DEPLOYMENT.md for admin credentials"

# Clean up
rm -f "$WEB_DIR/$PROJECT_NAME-production.tar.gz"
echo "🧹 Production archive cleaned up" 