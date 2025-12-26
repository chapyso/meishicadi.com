# Deployment Instructions for Profile Photo Upload Fix

## Files Modified

The following files have been modified and need to be uploaded to the server:

1. **app/Models/Utility.php** - Fixed file upload path and URL generation
2. **app/Http/Controllers/BusinessController.php** - Fixed form submission and error handling
3. **resources/views/business/edit.blade.php** - Fixed file input handling and form submission
4. **public/custom/js/custom.js** - Fixed selectFile function to prevent duplicate handlers

## Upload Instructions

### Option 1: Using FTP/SFTP Client (FileZilla, Cyberduck, etc.)

1. Connect to your server: `consulum.meishicadi.com`
2. Navigate to your Laravel project root directory
3. Upload the following files maintaining the directory structure:
   - `app/Models/Utility.php`
   - `app/Http/Controllers/BusinessController.php`
   - `resources/views/business/edit.blade.php`
   - `public/custom/js/custom.js`

### Option 2: Using SSH/SCP

```bash
# From your local machine, navigate to the project directory
cd ~/Desktop/WEB\ PROJECTS/Meishibios

# Upload files (replace USERNAME and SERVER with your credentials)
scp app/Models/Utility.php USERNAME@consulum.meishicadi.com:/path/to/project/app/Models/
scp app/Http/Controllers/BusinessController.php USERNAME@consulum.meishicadi.com:/path/to/project/app/Http/Controllers/
scp resources/views/business/edit.blade.php USERNAME@consulum.meishicadi.com:/path/to/project/resources/views/business/
scp public/custom/js/custom.js USERNAME@consulum.meishicadi.com:/path/to/project/public/custom/js/
```

## Post-Deployment Steps (IMPORTANT!)

After uploading the files, SSH into your server and run:

```bash
# 1. Navigate to project directory
cd /path/to/your/project

# 2. Create storage symlink and directories (CRITICAL for images to work)
# Option A: Use the custom command (recommended)
php artisan storage:fix-symlink

# Option B: Manual method
php artisan storage:link
mkdir -p storage/app/public/card_logo
mkdir -p storage/app/public/card_banner
chmod -R 755 storage/app/public

# 3. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 4. Set proper permissions (if needed)
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Verification

After deployment, verify:
1. Profile photo upload works
2. Images are saved to `storage/app/public/card_logo/`
3. Images are accessible via `/storage/card_logo/filename.jpg`
4. The symlink exists: `public/storage -> storage/app/public`

## Troubleshooting

If images still don't work:
1. Check if `public/storage` symlink exists: `ls -la public/storage`
2. If symlink doesn't exist, run: `php artisan storage:link`
3. Check file permissions: `chmod -R 755 storage/app/public`
4. Verify files are being uploaded: `ls -la storage/app/public/card_logo/`

