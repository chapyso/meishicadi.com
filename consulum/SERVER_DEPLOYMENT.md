# Server Deployment Guide - consulum.meishicadi.com

## Server Information

- **Server IP**: 148.135.129.127
- **SSH Port**: 65002
- **SSH User**: u916293666
- **Deployment Path**: `/home/u916293666/domains/meishicadi.com/public_html/consulum`
- **Public Directory**: `/home/u916293666/domains/meishicadi.com/public_html/consulum/public`

## Step 1: Connect to Server

```bash
ssh -p 65002 u916293666@148.135.129.127
```

## Step 2: Navigate to Deployment Directory

```bash
cd /home/u916293666/domains/meishicadi.com/public_html/consulum
```

## Step 3: Upload Project Files

Upload all project files from your local machine to the server. You can use:

### Option A: SCP (from your local machine)
```bash
# From your local HOONA directory
scp -P 65002 -r * u916293666@148.135.129.127:/home/u916293666/domains/meishicadi.com/public_html/consulum/
```

### Option B: SFTP or FTP Client
- Host: 148.135.129.127
- Port: 65002
- Username: u916293666
- Upload to: `/home/u916293666/domains/meishicadi.com/public_html/consulum/`

### Option C: Git (if repository exists)
```bash
cd /home/u916293666/domains/meishicadi.com/public_html/consulum
git clone [your-repo-url] .
```

## Step 4: Update .env File

Once files are uploaded, edit the `.env` file on the server:

```bash
nano /home/u916293666/domains/meishicadi.com/public_html/consulum/.env
```

**IMPORTANT**: Update these values:
- `DB_HOST` - Usually `localhost` or `127.0.0.1`
- `DB_DATABASE` - Your database name (likely `u916293666_vcardsaas`)
- `DB_USERNAME` - Your database username
- `DB_PASSWORD` - Your database password

Verify these are set correctly:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://consulum.meishicadi.com`

## Step 5: Run Deployment Script

After uploading the `deploy-server.sh` script:

```bash
cd /home/u916293666/domains/meishicadi.com/public_html/consulum
chmod +x deploy-server.sh
./deploy-server.sh
```

## Step 6: Manual Deployment (Alternative)

If you prefer to run commands manually:

```bash
cd /home/u916293666/domains/meishicadi.com/public_html/consulum

# Install dependencies
composer install --optimize-autoloader --no-dev

# Verify .env exists
ls -la .env

# Run migrations (if needed)
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear and cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache
```

## Step 7: Web Server Configuration

### Apache Configuration

Your `.htaccess` in the `public` directory should already be configured. Ensure Apache's document root points to:

```
/home/u916293666/domains/meishicadi.com/public_html/consulum/public
```

### Nginx Configuration

If using Nginx, your server block should point to:

```nginx
root /home/u916293666/domains/meishicadi.com/public_html/consulum/public;
```

## Step 8: Set Proper Permissions

```bash
cd /home/u916293666/domains/meishicadi.com/public_html/consulum

# Set ownership (adjust user:group as needed)
chown -R u916293666:u916293666 .

# Set permissions
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/storage
```

## Step 9: Verify Deployment

1. **Check Application Status**
   ```bash
   cd /home/u916293666/domains/meishicadi.com/public_html/consulum
   php artisan about
   ```

2. **Test URL**: Visit `https://consulum.meishicadi.com` in your browser

3. **Check Logs**
   ```bash
   tail -f /home/u916293666/domains/meishicadi.com/public_html/consulum/storage/logs/laravel.log
   ```

## Troubleshooting

### Issue: 500 Internal Server Error
```bash
# Check logs
tail -50 storage/logs/laravel.log

# Verify permissions
ls -la storage/ bootstrap/cache/

# Re-cache config
php artisan config:cache
```

### Issue: Assets Not Loading
```bash
# Recreate storage link
php artisan storage:link

# Clear view cache
php artisan view:clear
```

### Issue: Database Connection Failed
```bash
# Test database connection
php artisan tinker
# Then: DB::connection()->getPdo();
```

### Issue: Permission Denied
```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R u916293666:u916293666 storage bootstrap/cache
```

## File Structure on Server

```
/home/u916293666/domains/meishicadi.com/public_html/consulum/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/              ← Web server points here
│   ├── index.php
│   ├── .htaccess
│   └── storage/        ← Symlink to storage/app/public
├── resources/
├── routes/
├── storage/            ← Must be writable (775)
│   ├── app/
│   ├── framework/
│   └── logs/
├── vendor/
├── .env                ← Important: Contains production config
├── artisan
├── composer.json
└── deploy-server.sh    ← Deployment script
```

## Security Checklist

- [x] `APP_ENV=production`
- [x] `APP_DEBUG=false`
- [x] `APP_URL=https://consulum.meishicadi.com`
- [x] `.env` file has correct database credentials
- [ ] SSL certificate installed
- [ ] File permissions set correctly
- [ ] Storage directory is writable

## Quick Reference Commands

```bash
# Connect to server
ssh -p 65002 u916293666@148.135.129.127

# Go to project
cd /home/u916293666/domains/meishicadi.com/public_html/consulum

# View logs
tail -f storage/logs/laravel.log

# Clear caches
php artisan optimize:clear

# Re-cache for production
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

**Last Updated**: $(date)
**Domain**: consulum.meishicadi.com
**Deployment Path**: `/home/u916293666/domains/meishicadi.com/public_html/consulum`



