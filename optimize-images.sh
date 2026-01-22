#!/bin/bash

#############################################
# Image Optimization Script
# Converts images to WebP and optimizes them
#############################################

echo "🖼️  Starting image optimization..."

# Install required tools if not present
if ! command -v cwebp &> /dev/null; then
    echo "Installing webp tools..."
    sudo apt-get update
    sudo apt-get install -y webp
fi

if ! command -v optipng &> /dev/null; then
    echo "Installing optipng..."
    sudo apt-get install -y optipng
fi

if ! command -v jpegoptim &> /dev/null; then
    echo "Installing jpegoptim..."
    sudo apt-get install -y jpegoptim
fi

# Directories to optimize
DIRS=(
    "/home/ubuntu/live-carphatian/public/images"
    "/home/ubuntu/live-carphatian/public/storage"
)

optimized_count=0
webp_count=0

for dir in "${DIRS[@]}"; do
    if [ ! -d "$dir" ]; then
        echo "⚠️  Directory not found: $dir"
        continue
    fi
    
    echo "Processing directory: $dir"
    
    # Optimize PNG files
    find "$dir" -type f -iname "*.png" ! -name "*.webp" | while read file; do
        echo "  Optimizing PNG: $(basename "$file")"
        optipng -o7 -quiet "$file" 2>/dev/null
        
        # Create WebP version
        cwebp -q 85 "$file" -o "${file%.png}.webp" 2>/dev/null
        ((webp_count++))
        ((optimized_count++))
    done
    
    # Optimize JPG/JPEG files
    find "$dir" -type f \( -iname "*.jpg" -o -iname "*.jpeg" \) ! -name "*.webp" | while read file; do
        echo "  Optimizing JPG: $(basename "$file")"
        jpegoptim --max=85 --strip-all --quiet "$file" 2>/dev/null
        
        # Create WebP version
        cwebp -q 85 "$file" -o "${file%.*}.webp" 2>/dev/null
        ((webp_count++))
        ((optimized_count++))
    done
done

echo ""
echo "✅ Image optimization complete!"
echo "   Optimized: $optimized_count images"
echo "   WebP created: $webp_count images"
