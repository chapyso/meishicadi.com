# Meishicadi - Deployment Guide

## 🚀 SSH Deployment Instructions

### Prerequisites
- SSH access to your server
- PHP 8.1+ installed
- MySQL/MariaDB database
- Composer installed
- Web server (Apache/Nginx)

### Step 1: Prepare Your Local Project

1. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

2. **Optimize for Production**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan view:cache
   php artisan route:cache
   ```

3. **Create Production Archive**
   ```bash
   tar -czf japy-tag-production.tar.gz --exclude=node_modules --exclude=.git --exclude=storage/logs --exclude=storage/framework/cache --exclude=storage/framework/sessions --exclude=storage/framework/views .
   ```

### Step 2: Upload to Server

1. **Upload via SCP**
   ```bash
   scp japy-tag-production.tar.gz username@your-server.com:/path/to/web/directory/
   ```

2. **SSH into your server**
   ```bash
   ssh username@your-server.com
   ```

3. **Extract the archive**
   ```bash
   cd /path/to/web/directory/
   tar -xzf japy-tag-production.tar.gz
   ```

### Step 3: Server Configuration

1. **Set up environment file**
   ```bash
   cp .env.production .env
   # Edit .env with your production settings
   nano .env
   ```

2. **Set proper permissions**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   chmod -R 644 .env
   ```

3. **Run deployment script**
   ```bash
   chmod +x deploy.sh
   ./deploy.sh
   ```

### Step 4: Database Setup

1. **Create database**
   ```sql
   CREATE DATABASE japy_tag_production;
   ```

2. **Run migrations**
   ```bash
   php artisan migrate --force
   ```

3. **Seed database (optional)**
   ```bash
   php artisan db:seed --force
   ```

### Step 5: Web Server Configuration

#### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/web/directory/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Step 6: Final Steps

1. **Create storage link**
   ```bash
   php artisan storage:link
   ```

2. **Clear all caches**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

3. **Optimize for production**
   ```bash
   php artisan config:cache
   php artisan view:cache
   php artisan route:cache
   ```

### 🔧 Environment Variables

Update your `.env` file with:

```env
APP_NAME="Meishicadi"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://meishicadi.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=meishicadi_production
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.titan.email
MAIL_PORT=465
MAIL_USERNAME=no-reply@meishicadi.com
MAIL_PASSWORD=Abantu@256
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=no-reply@meishicadi.com
MAIL_FROM_NAME="Meishicadi"
```

### 🛠️ Troubleshooting

1. **Permission Issues**
   ```bash
   chown -R www-data:www-data /path/to/web/directory/
   chmod -R 755 storage/ bootstrap/cache/
   ```

2. **Cache Issues**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

3. **Storage Link Issues**
   ```bash
   php artisan storage:link
   ```

### 📋 Post-Deployment Checklist

- [ ] Application loads without errors
- [ ] Database connections work
- [ ] Email sending is configured
- [ ] File uploads work
- [ ] Admin panel is accessible
- [ ] Notification templates feature works
- [ ] Front CMS styling is applied
- [ ] SSL certificate is installed (if using HTTPS)

### 🚨 Security Notes

1. **Never commit `.env` files**
2. **Use strong database passwords**
3. **Enable HTTPS in production**
4. **Regular backups of database and files**
5. **Keep dependencies updated**

---

**🎉 Your Meishicadi application is now deployed and ready!** 