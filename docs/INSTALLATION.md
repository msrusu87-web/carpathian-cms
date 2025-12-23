# 📥 Carpathian CMS Installation Guide

Complete step-by-step guide to install Carpathian CMS on your server.

## 📋 Table of Contents

- [System Requirements](#system-requirements)
- [Server Setup](#server-setup)
- [Installation Steps](#installation-steps)
- [Post-Installation](#post-installation)
- [Troubleshooting](#troubleshooting)

---

## 🖥️ System Requirements

### Minimum Requirements
- **PHP:** 8.4 or higher
- **MySQL:** 8.0 or higher (or MariaDB 10.11+)
- **Web Server:** Nginx 1.20+ or Apache 2.4+
- **Composer:** 2.x
- **Node.js:** 18.x or higher
- **NPM:** 9.x or higher

### PHP Extensions Required
```bash
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- PDO_MySQL
- Tokenizer
- XML
- GD or Imagick
- Zip
- Curl
```

### Recommended Server Specs
- **RAM:** 2GB minimum, 4GB+ recommended
- **Disk Space:** 10GB minimum
- **CPU:** 2 cores minimum

---

## 🔧 Server Setup

### Ubuntu 22.04/24.04 Setup

#### 1. Update System
```bash
sudo apt update && sudo apt upgrade -y
```

#### 2. Install PHP 8.4 and Extensions
```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP and extensions
sudo apt install -y php8.4-fpm php8.4-cli php8.4-common php8.4-mysql \
php8.4-zip php8.4-gd php8.4-mbstring php8.4-curl php8.4-xml \
php8.4-bcmath php8.4-intl php8.4-redis
```

#### 3. Install MySQL
```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Create database
sudo mysql -u root -p
```

```sql
CREATE DATABASE carpathian_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'carpathian'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON carpathian_cms.* TO 'carpathian'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### 4. Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

#### 5. Install Node.js & NPM
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

#### 6. Install Nginx
```bash
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx
```

---

## 📦 Installation Steps

### Step 1: Clone Repository

```bash
cd /var/www
sudo git clone https://github.com/msrusu87-web/carpathian-cms.git carpathian.ro
cd carpathian.ro
```

### Step 2: Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/carpathian.ro
sudo chmod -R 775 /var/www/carpathian.ro/storage
sudo chmod -R 775 /var/www/carpathian.ro/bootstrap/cache
```

### Step 3: Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### Step 4: Install Node Dependencies

```bash
npm install
npm run build
```

### Step 5: Configure Environment

```bash
cp .env.example .env
nano .env
```

Update the following in `.env`:

```env
APP_NAME="Carpathian CMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_DATABASE=carpathian_cms
DB_USERNAME=carpathian
DB_PASSWORD=your_strong_password

# Optional AI features
GROQ_API_KEY=your_groq_api_key
```

### Step 6: Generate Application Key

```bash
php artisan key:generate
```

### Step 7: Run Database Migrations

```bash
php artisan migrate --seed
```

This will create all tables and populate with sample data.

### Step 8: Create Admin User

```bash
php artisan make:filament-user
```

Follow the prompts:
- Name: Your Name
- Email: admin@yourdomain.com  
- Password: Choose a strong password

### Step 9: Optimize Application

```bash
php artisan optimize
php artisan storage:link
php artisan filament:optimize
```

---

## 🌐 Nginx Configuration

Create Nginx site configuration:

```bash
sudo nano /etc/nginx/sites-available/carpathian.ro
```

Add the following configuration:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/carpathian.ro/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml font/truetype font/opentype application/vnd.ms-fontobject image/svg+xml;

    # Client upload limit
    client_max_body_size 20M;
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/carpathian.ro /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🔒 SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

---

## ⚙️ Post-Installation

### 1. Configure Cron Jobs

Add to crontab:

```bash
sudo crontab -e
```

Add this line:

```cron
* * * * * cd /var/www/carpathian.ro && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Setup Queue Worker (Optional)

For background jobs:

```bash
sudo nano /etc/supervisor/conf.d/carpathian-worker.conf
```

```ini
[program:carpathian-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/carpathian.ro/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/carpathian.ro/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start carpathian-worker:*
```

### 3. Configure Firewall

```bash
sudo ufw allow 'Nginx Full'
sudo ufw allow OpenSSH
sudo ufw enable
```

### 4. Access Your Site

- **Frontend:** https://your-domain.com
- **Admin Panel:** https://your-domain.com/admin
- **Login:** Use the admin credentials you created earlier

---

## 🎨 Optional: Sample Data

If you want sample data (pages, blog posts, products):

```bash
php artisan db:seed --class=SampleDataSeeder
```

This will populate:
- 5 Sample Pages
- 10 Blog Posts
- 20 Products
- 5 Categories
- Sample Menu Items

---

## 🔧 Troubleshooting

### Permission Issues

```bash
sudo chown -R www-data:www-data /var/www/carpathian.ro
sudo chmod -R 775 /var/www/carpathian.ro/storage
sudo chmod -R 775 /var/www/carpathian.ro/bootstrap/cache
```

### 500 Server Error

```bash
# Check Laravel logs
tail -f /var/www/carpathian.ro/storage/logs/laravel.log

# Check Nginx logs
sudo tail -f /var/log/nginx/error.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Database Connection Issues

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check MySQL is running
sudo systemctl status mysql

# Check credentials in .env file
```

### Composer/NPM Issues

```bash
# Clear Composer cache
composer clear-cache
composer install --no-cache

# Clear NPM cache
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

### File Upload Issues

```bash
# Check PHP upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Edit PHP configuration
sudo nano /etc/php/8.4/fpm/php.ini

# Set these values:
upload_max_filesize = 20M
post_max_size = 20M

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

---

## 📞 Need Help?

- 📧 Email: support@carphatian.ro
- 🐛 Report Issues: [GitHub Issues](https://github.com/msrusu87-web/carpathian-cms/issues)
- 💬 Community: [GitHub Discussions](https://github.com/msrusu87-web/carpathian-cms/discussions)
- 📚 Documentation: [Full Docs](README.md)

---

## ✅ Installation Checklist

- [ ] Server requirements met
- [ ] PHP 8.4+ installed with all extensions
- [ ] MySQL 8.0+ installed and configured
- [ ] Composer installed
- [ ] Node.js 18+ installed
- [ ] Repository cloned
- [ ] Dependencies installed (Composer & NPM)
- [ ] .env configured
- [ ] Database migrated
- [ ] Admin user created
- [ ] Nginx configured
- [ ] SSL certificate installed
- [ ] Permissions set correctly
- [ ] Cron jobs configured
- [ ] Firewall configured
- [ ] Site accessible

---

**Next Steps:** [Configuration Guide](CONFIGURATION.md) | [Customization](CUSTOMIZATION.md)

[⬆ Back to Main README](../README.md)
