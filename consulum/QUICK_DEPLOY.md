# Quick Deployment Guide

## Server Connection
```bash
ssh -p 65002 u916293666@148.135.129.127
```

## Before You Start

1. **Upload all files** to your server (usually to `public_html` or your web root)
2. **Update `.env` file** with production database credentials:
   - `DB_HOST`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`

## Deployment Methods

### Option 1: Use the Deployment Script (Recommended)

Once you're connected via SSH and in your project directory:

```bash
# Make script executable (if not already)
chmod +x deploy.sh

# Run the deployment script
./deploy.sh
```

### Option 2: Manual Deployment Commands

If you prefer to run commands manually:

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate key (if needed)
php artisan key:generate

# Run migrations (if needed)
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear caches
php artisan optimize:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache
```

## Important Notes

- **Document Root**: Your web server should point to the `public` directory
- **SSL Certificate**: Ensure SSL is configured for `consulum.meishicadi.com`
- **Database**: Update credentials in `.env` before running migrations
- **Permissions**: Ensure `storage/` and `bootstrap/cache/` are writable

## Troubleshooting

If you encounter issues:

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Verify .env file exists
cat .env

# Check permissions
ls -la storage/ bootstrap/cache/

# Test database connection
php artisan tinker
# Then run: DB::connection()->getPdo();
```

## Verify Deployment

After deployment, test:
- ✅ https://consulum.meishicadi.com loads
- ✅ Login/Registration works
- ✅ File uploads work
- ✅ Emails are sending



