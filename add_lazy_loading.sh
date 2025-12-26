#!/bin/bash
# Add lazy loading to all images in Blade templates

VIEWS_DIR="domains/magicsafaris.com/public_html/resources/views/frontend"

echo "Adding lazy loading to images..."

# Find all blade files and add loading="lazy" to img tags that don't already have it
find "$VIEWS_DIR" -type f -name "*.blade.php" -exec sed -i 's/<img\([^>]*\)src=/<img\1loading="lazy" src=/g' {} \;

# Fix double loading attributes if any
find "$VIEWS_DIR" -type f -name "*.blade.php" -exec sed -i 's/loading="lazy" loading="lazy"/loading="lazy"/g' {} \;

echo "Lazy loading added to all images!"

