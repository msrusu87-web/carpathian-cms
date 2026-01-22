#!/bin/bash

#############################################
# Quick Performance Deployment Script
# Safe deployment with automatic backup
#############################################

SITE_DIR="/home/ubuntu/live-carphatian"

echo "🚀 Carpathian CMS - Performance Optimization Deployment"
echo "========================================================"
echo ""

# Step 1: Create backup
echo "📦 Step 1: Creating backup..."
cd "$SITE_DIR"
./backup-manager.sh backup

if [ $? -ne 0 ]; then
    echo "❌ Backup failed! Aborting."
    exit 1
fi

echo "✅ Backup created successfully"
echo ""

# Step 2: Clear caches
echo "🧹 Step 2: Clearing caches..."
php artisan cache:clear > /dev/null 2>&1
php artisan config:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1
php artisan route:clear > /dev/null 2>&1
echo "✅ Caches cleared"
echo ""

# Step 3: Optimize images (optional)
read -p "🖼️  Step 3: Optimize images? (yes/no): " optimize_images

if [ "$optimize_images" = "yes" ]; then
    echo "Optimizing images... (this may take a while)"
    ./optimize-images.sh
    echo "✅ Images optimized"
else
    echo "⏭️  Skipping image optimization"
fi
echo ""

# Step 4: Generate sitemaps
echo "🗺️  Step 4: Generating sitemaps..."
curl -s https://carphatian.ro/generate-sitemap > /dev/null
curl -s https://carphatian.ro/generate-news-sitemap > /dev/null
echo "✅ Sitemaps generated"
echo ""

# Step 5: Set permissions
echo "🔒 Step 5: Setting permissions..."
chmod 644 public/robots.txt
chmod 644 public/sitemap.xml
chmod 644 public/sitemap-news.xml 2>/dev/null
chmod -R 755 storage
chmod -R 755 bootstrap/cache
echo "✅ Permissions set"
echo ""

# Step 6: Restart services
echo "🔄 Step 6: Restarting services..."
read -p "Restart PHP-FPM and Nginx? (yes/no): " restart_services

if [ "$restart_services" = "yes" ]; then
    sudo systemctl restart php8.3-fpm
    sudo systemctl restart nginx
    echo "✅ Services restarted"
else
    echo "⏭️  Skipping service restart"
fi
echo ""

# Step 7: Verify deployment
echo "✅ Deployment Complete!"
echo ""
echo "📊 Next Steps:"
echo "1. Test your site: https://carphatian.ro/"
echo "2. Check PageSpeed: https://pagespeed.web.dev/analysis?url=https://carphatian.ro/"
echo "3. Submit sitemaps to Google Search Console:"
echo "   - https://carphatian.ro/sitemap.xml"
echo "   - https://carphatian.ro/sitemap-news.xml"
echo ""
echo "🛡️  Backup available at: $(ls -t /home/ubuntu/backups/carphatian-cms/ | head -1)"
echo ""
echo "📝 If you need to rollback:"
echo "   ./backup-manager.sh restore <backup_name>"
echo ""
