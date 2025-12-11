# 📦 Carpathian CMS - Installation Guide

Complete guide for installing Carpathian CMS on any server.

## 🎯 What's Included

This package includes:
- ✅ Complete CMS with Filament admin panel
- ✅ Demo content (products, pages, blog posts)
- ✅ Multilingual support (6 languages)
- ✅ E-commerce functionality
- ✅ Automated installer with requirement checks
- ✅ Full database with sample data

## 🚀 Quick Installation (3 Steps)

### Step 1: Upload Files & Install Dependencies

```bash
cd /var/www/cms.carphatian.ro

# Install Composer dependencies (if vendor folder not included)
composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets (if needed)
npm install && npm run build
```

### Step 2: Set Permissions

```bash
# Set correct ownership
sudo chown -R www-data:www-data .

# Set correct permissions
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
sudo chmod +x artisan
```

### Step 3: Run Web Installer

Visit `http://your-domain.com/install.php` in your browser.

The installer will automatically:
1. ✅ Check all server requirements
2. ✅ Show what's missing (if anything)
3. ✅ Guide you through database setup
4. ✅ Configure your site settings
5. ✅ Import database with all content
6. ✅ Create your admin account

## 📝 Post-Installation

```bash
# Delete the installer for security
rm install.php

# Optionally install SSL
sudo certbot --nginx -d your-domain.com
```

## 🎨 Access Your CMS

- **Frontend:** http://your-domain.com
- **Admin Panel:** http://your-domain.com/admin

---

**Full documentation:** See README.md and REQUIREMENTS.md
