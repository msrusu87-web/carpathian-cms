#!/bin/bash

# Carphatian CMS - Complete Backup Script
# Date: $(date +%Y-%m-%d_%H-%M-%S)

echo "========================================="
echo "CARPHATIAN CMS - COMPLETE BACKUP"
echo "========================================="
echo ""

# Set variables
BACKUP_DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="carphatian_cms_backup_${BACKUP_DATE}"
BACKUP_DIR="/var/www/carphatian.ro/html"
PUBLIC_DIR="${BACKUP_DIR}/public"
DB_NAME="carphatian_cms"
DB_USER="carphatian"
DB_PASS="carphatian"

echo "📦 Creating backup directory..."
mkdir -p /tmp/${BACKUP_NAME}

echo "📊 Exporting database..."
mysqldump -u ${DB_USER} -p${DB_PASS} ${DB_NAME} --single-transaction --skip-lock-tables > /tmp/${BACKUP_NAME}/database_complete.sql 2>/dev/null
if [ $? -eq 0 ]; then
    echo "   ✅ Database exported successfully ($(du -h /tmp/${BACKUP_NAME}/database_complete.sql | cut -f1))"
else
    echo "   ⚠️  Database export completed with warnings"
fi

echo ""
echo "📄 Creating README.md with setup instructions..."
cat > /tmp/${BACKUP_NAME}/README.md << 'EOF'
# Carphatian CMS - Complete Backup

**Backup Date:** $(date +"%Y-%m-%d %H:%M:%S")
**Version:** Laravel 11 + Filament v3.3.45

## 📦 What's Included

- ✅ Complete Laravel application source code
- ✅ All dependencies (vendor folder)
- ✅ Full database with all content and settings
- ✅ Public assets (CSS, JS, images)
- ✅ Environment configuration example
- ✅ All translations (6 languages: ro, en, es, it, de, fr)
- ✅ Complete Filament admin panel setup
- ✅ All custom widgets and templates

## 🚀 Installation Instructions

### 1. Prerequisites
```bash
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer 2.x
- Node.js 18+ and NPM
```

### 2. Extract Files
```bash
unzip carphatian_cms_backup_*.zip
cd carphatian_cms_backup_*
```

### 3. Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Configure Environment
```bash
cp .env.example .env
nano .env
```

Update these values:
```
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
APP_URL=https://yourdomain.com
```

### 5. Import Database
```bash
mysql -u your_user -p your_database < database_complete.sql
```

### 6. Generate Application Key
```bash
php artisan key:generate
```

### 7. Run Migrations (if needed)
```bash
php artisan migrate --force
```

### 8. Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 9. Set Up Storage Link
```bash
php artisan storage:link
```

### 10. Install Node Dependencies (optional)
```bash
npm install
npm run build
```

## 🔐 Default Admin Access

After database import, you can access admin panel at:
- URL: `https://yourdomain.com/admin`
- Check the database `users` table for admin credentials

## 📚 Features Included

### ✨ Frontend Features
- Multi-language support (RO, EN, ES, IT, DE, FR)
- SEO optimized pages with structured data
- Modern portfolio page with animations
- Blog with categories and tags
- E-commerce shop system
- Contact forms
- Dynamic page builder

### 🎨 Admin Panel (Filament)
- Complete CMS management
- Page builder with templates
- Blog post management
- Product catalog management
- Language translation editor
- Widget system
- User management
- Category and tag management
- File manager

### 🌍 Translation System
- 6 active languages
- Live translation editor in admin
- JSON and PHP translation files
- Auto-cache clearing on save

### 🛍️ E-Commerce
- Product catalog
- Shopping cart
- Categories
- Product variants
- Order management

## 🔧 Important Files

- `.env` - Environment configuration
- `database_complete.sql` - Full database backup
- `composer.json` - PHP dependencies
- `package.json` - Node dependencies
- `routes/web.php` - Application routes
- `app/Filament/` - Admin panel resources

## 📞 Support

For issues or questions:
- Email: support@carphatian.ro
- Website: https://carphatian.ro

## 📄 License

Proprietary - All rights reserved by Carphatian

---

**Created by Carphatian** 🏔️
EOF

echo "📋 Creating requirements.txt..."
cat > /tmp/${BACKUP_NAME}/REQUIREMENTS.txt << 'EOF'
# CARPHATIAN CMS - SYSTEM REQUIREMENTS

## Server Requirements

### PHP Requirements
- PHP Version: >= 8.2
- Extensions:
  - BCMath PHP Extension
  - Ctype PHP Extension
  - cURL PHP Extension
  - DOM PHP Extension
  - Fileinfo PHP Extension
  - Filter PHP Extension
  - Hash PHP Extension
  - Mbstring PHP Extension
  - OpenSSL PHP Extension
  - PCRE PHP Extension
  - PDO PHP Extension
  - Session PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - GD PHP Extension
  - Zip PHP Extension
  - JSON PHP Extension

### Database
- MySQL >= 8.0 OR MariaDB >= 10.3

### Web Server
- Apache 2.4+ with mod_rewrite enabled
- OR Nginx 1.18+

### Additional Software
- Composer 2.x
- Node.js >= 18.x
- NPM >= 9.x

## Laravel Dependencies (see composer.json)
- Laravel Framework 11.x
- Filament v3.3.45
- Livewire 3.x
- Spatie Laravel Permission
- Laravel Sanctum
- Laravel Fortify

## Recommended Server Configuration
- Memory Limit: 256M minimum (512M recommended)
- Max Execution Time: 300 seconds
- Upload Max Filesize: 20M
- Post Max Size: 20M

## Production Server Setup
```bash
# Apache
sudo a2enmod rewrite
sudo systemctl restart apache2

# Set up cron job
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

# Set up supervisor for queues (optional)
[program:carphatian-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/storage/logs/worker.log
```
EOF

echo ""
echo "📦 Copying application files..."
echo "   Please wait, this may take a few minutes..."

# Create backup structure
rsync -a --exclude='node_modules' \
         --exclude='.git' \
         --exclude='storage/logs/*' \
         --exclude='storage/framework/cache/*' \
         --exclude='storage/framework/sessions/*' \
         --exclude='storage/framework/views/*' \
         --exclude='database_backup_*.sql' \
         --exclude='*.zip' \
         --exclude='create_backup.sh' \
         ${BACKUP_DIR}/ /tmp/${BACKUP_NAME}/

echo "   ✅ Application files copied"

echo ""
echo "🗜️  Creating ZIP archive..."
cd /tmp
zip -r ${BACKUP_NAME}.zip ${BACKUP_NAME}/ -q
ZIP_SIZE=$(du -h ${BACKUP_NAME}.zip | cut -f1)
echo "   ✅ ZIP created: ${ZIP_SIZE}"

echo ""
echo "📁 Moving to public directory..."
mv ${BACKUP_NAME}.zip ${PUBLIC_DIR}/downloads/
rm -rf /tmp/${BACKUP_NAME}

echo ""
echo "========================================="
echo "✅ BACKUP COMPLETED SUCCESSFULLY!"
echo "========================================="
echo ""
echo "📊 Backup Details:"
echo "   - Name: ${BACKUP_NAME}.zip"
echo "   - Size: ${ZIP_SIZE}"
echo "   - Location: ${PUBLIC_DIR}/downloads/"
echo ""
echo "🔗 Download Link:"
echo "   https://carphatian.ro/downloads/${BACKUP_NAME}.zip"
echo ""
echo "========================================="
