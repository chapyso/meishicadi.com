#!/bin/bash
# Image Optimization Script for magicsafaris.com

UPLOAD_DIR="domains/magicsafaris.com/public_html/public/uploads"
FRONTEND_IMG_DIR="domains/magicsafaris.com/public_html/public/frontend/img"

echo "Starting image optimization..."

# Check if ImageMagick is available
if command -v convert &> /dev/null; then
    echo "ImageMagick found - using for optimization"
    
    # Optimize JPEG images larger than 500KB
    find "$UPLOAD_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" \) -size +500k | while read img; do
        echo "Optimizing: $img"
        convert "$img" -strip -quality 85 -interlace Plane "$img"
    done
    
    # Optimize PNG images larger than 200KB
    find "$UPLOAD_DIR" -type f -iname "*.png" -size +200k | while read img; do
        echo "Optimizing: $img"
        convert "$img" -strip -quality 90 "$img"
    done
    
    # Optimize frontend images
    find "$FRONTEND_IMG_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" \) | while read img; do
        echo "Optimizing: $img"
        if [[ "$img" == *.jpg ]] || [[ "$img" == *.jpeg ]]; then
            convert "$img" -strip -quality 85 -interlace Plane "$img"
        else
            convert "$img" -strip -quality 90 "$img"
        fi
    done
    
    echo "Image optimization complete!"
else
    echo "ImageMagick not found. Install it with: yum install ImageMagick or apt-get install imagemagick"
fi

