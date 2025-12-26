#!/bin/bash
# Quick Performance Optimization Script
# Run this script to apply all quick optimizations

cd ~

echo "=========================================="
echo "Website Performance Optimization"
echo "=========================================="
echo ""

# 1. Laravel Optimization
echo "1. Optimizing Laravel..."
cd domains/magicsafaris.com/public_html
php artisan config:clear && php artisan config:cache
php artisan route:clear && php artisan route:cache
php artisan view:clear && php artisan view:cache
php artisan cache:clear
php artisan optimize
echo "✓ Laravel optimized"
echo ""

# 2. Check for large images
echo "2. Checking for large images..."
LARGE_IMAGES=$(find public/uploads -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) -size +500k 2>/dev/null | wc -l)
if [ "$LARGE_IMAGES" -gt 0 ]; then
    echo "⚠ Found $LARGE_IMAGES large images (>500KB)"
    echo "  Run: ~/optimize_images.sh (requires ImageMagick)"
else
    echo "✓ No large images found"
fi
echo ""

# 3. Check CSS minification
echo "3. Checking CSS files..."
if [ -f "public/frontend/css/style.min.css" ]; then
    echo "✓ Minified CSS exists"
else
    echo "⚠ Minified CSS not found - run: ~/minify_css.sh"
fi
echo ""

# 4. Verify .htaccess optimizations
echo "4. Verifying .htaccess optimizations..."
if grep -q "mod_deflate" public/.htaccess 2>/dev/null; then
    echo "✓ Compression enabled"
else
    echo "⚠ Compression not found in .htaccess"
fi

if grep -q "mod_expires" public/.htaccess 2>/dev/null; then
    echo "✓ Browser caching enabled"
else
    echo "⚠ Browser caching not found in .htaccess"
fi
echo ""

# 5. Check debug mode
echo "5. Checking environment..."
if grep -q "APP_DEBUG=false" .env 2>/dev/null; then
    echo "✓ Debug mode disabled"
else
    echo "⚠ Debug mode may be enabled"
fi

if grep -q "APP_ENV=production" .env 2>/dev/null; then
    echo "✓ Production environment set"
else
    echo "⚠ Not set to production environment"
fi
echo ""

echo "=========================================="
echo "Optimization Complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Compress large images: ~/optimize_images.sh"
echo "2. Minify CSS: ~/minify_css.sh"
echo "3. Review: ~/PERFORMANCE_GUIDE.md"
echo ""

