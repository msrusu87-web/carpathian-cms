# 📋 Carpathian CMS - System Requirements

## Minimum System Requirements

### Server Environment
- **Operating System**: Linux (Ubuntu 20.04+, Debian 11+, CentOS 8+) or Windows Server 2019+
- **Web Server**: Nginx 1.18+ or Apache 2.4+
- **Database**: MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 12+
- **PHP**: 8.1, 8.2, or 8.3
- **Memory**: Minimum 512MB RAM (1GB+ recommended)
- **Disk Space**: Minimum 500MB free space (1GB+ recommended)
- **CPU**: 1 core minimum (2+ cores recommended)

### Required PHP Extensions
```
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- PDO_MySQL (or PDO_PostgreSQL)
- Tokenizer
- XML
- GD or Imagick
- Zip
```

### Additional Software
- **Composer**: 2.5+
- **Node.js**: 18.x or 20.x
- **NPM**: 9.x or 10.x
- **Git**: 2.30+ (for version control)

## Recommended Configuration

### PHP Configuration (php.ini)
```ini
memory_limit = 256M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
max_input_vars = 3000
```

### MySQL/MariaDB Configuration
```ini
max_connections = 150
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
```

## Detailed Requirements by Component

### Core CMS
| Component | Requirement |
|-----------|-------------|
| Laravel Framework | 10.x |
| PHP Version | 8.1+ |
| Database | MySQL 5.7+ / MariaDB 10.3+ |
| Web Server | Nginx / Apache with mod_rewrite |
| SSL Certificate | Recommended for production |

### Frontend Assets
| Component | Version |
|-----------|---------|
| Tailwind CSS | 3.x |
| Alpine.js | 3.x |
| Vite | 4.x |

### Admin Panel
| Component | Requirement |
|-----------|-------------|
| Filament | 3.x |
| Livewire | 3.x |

### E-Commerce Plugin
| Feature | Requirement |
|---------|-------------|
| Payment Gateway | Stripe/PayPal integration ready |
| Currency Support | RON (default), EUR, USD |
| SSL Certificate | **Required** for checkout |

## PHP Extensions Details

### Required Extensions
```bash
# Ubuntu/Debian
sudo apt install php8.3-fpm php8.3-mysql php8.3-mbstring \
                 php8.3-xml php8.3-curl php8.3-gd \
                 php8.3-zip php8.3-bcmath php8.3-intl

# CentOS/RHEL
sudo yum install php83-php-fpm php83-php-mysqlnd php83-php-mbstring \
                 php83-php-xml php83-php-curl php83-php-gd \
                 php83-php-zip php83-php-bcmath php83-php-intl
```

### Optional but Recommended
- **Redis** - For caching and session management
- **Memcached** - Alternative caching solution
- **OPcache** - PHP opcode caching for performance
- **APCu** - Additional caching layer

## Database Requirements

### MySQL/MariaDB
```sql
-- Minimum privileges required
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, 
      INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, 
      EXECUTE, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, 
      ALTER ROUTINE, TRIGGER, REFERENCES
ON database_name.* TO 'user'@'localhost';
```

### Storage Requirements
- **Minimum**: 200MB for database
- **Recommended**: 1GB+ for growth
- **File Storage**: Separate disk/partition recommended for uploads

## Performance Requirements

### For Small Sites (< 1000 visitors/day)
- 1 CPU core
- 1GB RAM
- 10GB SSD storage

### For Medium Sites (1000-10,000 visitors/day)
- 2 CPU cores
- 2GB RAM
- 20GB SSD storage
- CDN recommended

### For Large Sites (> 10,000 visitors/day)
- 4+ CPU cores
- 4GB+ RAM
- 50GB+ SSD storage
- CDN required
- Load balancer recommended
- Database replication recommended

## Security Requirements

### SSL/TLS
- **Development**: Self-signed certificate acceptable
- **Production**: Valid SSL certificate required (Let's Encrypt recommended)

### Firewall Rules
```bash
# Allow HTTP/HTTPS
Port 80 (HTTP)
Port 443 (HTTPS)

# Database (restrict to localhost)
Port 3306 (MySQL) - localhost only

# SSH (if remote access needed)
Port 22 (SSH) - IP whitelist recommended
```

### File Permissions
```bash
# Directories
755 for directories

# Files
644 for files

# Writable directories
775 for storage/ bootstrap/cache/

# Owner
www-data:www-data (Ubuntu/Debian)
nginx:nginx or apache:apache (others)
```

## Browser Compatibility

### Admin Panel
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### Frontend (Public Site)
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+
- Mobile browsers (iOS Safari 13+, Chrome Mobile 80+)

## Third-Party Services (Optional)

### Email Services
- SMTP server (required for transactional emails)
- Recommended: Mailgun, SendGrid, Amazon SES

### Storage Services
- Local storage (default)
- Optional: Amazon S3, DigitalOcean Spaces, Cloudflare R2

### Analytics
- Google Analytics (optional)
- Matomo (optional)

### CDN Services (Recommended for Production)
- Cloudflare
- Amazon CloudFront
- DigitalOcean Spaces CDN

## Development Requirements

### Local Development Environment
```bash
# Option 1: Laravel Sail (Docker)
- Docker Desktop 4.x+
- Docker Compose 2.x+

# Option 2: Laravel Valet (macOS)
- PHP 8.1+
- Composer
- Valet 4.x+

# Option 3: Laragon (Windows)
- Laragon 5.x+
- PHP 8.1+
- MySQL 8.x+

# Option 4: Manual Setup
- XAMPP/WAMP
- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js 18+
```

## Installation Verification

### Check PHP Version
```bash
php -v
# Should show: PHP 8.1.x or higher
```

### Check Required Extensions
```bash
php -m | grep -E "curl|mbstring|xml|gd|zip|pdo|mysql"
```

### Check Composer
```bash
composer --version
# Should show: Composer version 2.5.x or higher
```

### Check Node.js & NPM
```bash
node --version  # Should show: v18.x or v20.x
npm --version   # Should show: 9.x or 10.x
```

### Check Database Connection
```bash
mysql -u root -p -e "SELECT VERSION();"
# Should connect successfully and show MySQL version
```

## Troubleshooting Common Issues

### Issue: PHP version too old
```bash
# Ubuntu 22.04+
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.3
```

### Issue: Missing PHP extensions
```bash
# Install all required extensions at once
sudo apt install php8.3-{fpm,mysql,mbstring,xml,curl,gd,zip,bcmath,intl}
```

### Issue: Composer not installed
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Issue: Node.js version mismatch
```bash
# Using NVM (recommended)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
nvm install 20
nvm use 20
```

## Support & Documentation

- **Installation Guide**: See [README.md](README.md)
- **Translation System**: See [lang/TRANSLATION_GUIDE.md](lang/TRANSLATION_GUIDE.md)
- **Laravel Documentation**: https://laravel.com/docs/10.x
- **Filament Documentation**: https://filamentphp.com/docs

## License Requirements

This CMS is open-source under the MIT License. No commercial license required.

---

**Last Updated**: December 2025  
**CMS Version**: 1.0.0  
**Minimum PHP**: 8.1  
**Recommended PHP**: 8.3

---

## 🔐 File Permissions & Ownership (CRITICAL)

### Correct File Permissions for Laravel

Laravel requires specific file permissions to function correctly. **Failure to set these permissions properly will result in errors during installation and operation.**

#### Quick Setup Commands

```bash
# Navigate to your project directory
cd /var/www/cms.carphatian.ro

# Set owner to web server user (Ubuntu/Debian)
sudo chown -R www-data:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 755 {} \;

# Set file permissions
sudo find . -type f -exec chmod 644 {} \;

# Set writable permissions for storage and cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Set executable permission for artisan
sudo chmod +x artisan
```

#### Detailed Permissions Breakdown

| Directory/File | Owner | Group | Permissions | Why |
|----------------|-------|-------|-------------|-----|
| `/` (root) | www-data | www-data | 755 | Base directory |
| `storage/*` | www-data | www-data | 775 | Laravel writes logs, cache, sessions |
| `bootstrap/cache/*` | www-data | www-data | 775 | Compiled classes, routes cache |
| `.env` | www-data | www-data | 644 | Configuration (sensitive!) |
| `artisan` | www-data | www-data | 755 | Artisan CLI tool |

### Automated Permission Script

```bash
#!/bin/bash
# Fix Permissions Script
cd /var/www/cms.carphatian.ro
sudo chown -R www-data:www-data .
sudo find . -type d -exec chmod 755 {} \;
sudo find . -type f -exec chmod 644 {} \;
sudo chmod -R 775 storage bootstrap/cache
sudo chmod +x artisan
```

## 📦 Database Configuration

### Required MySQL Privileges

```sql
CREATE DATABASE carpathian_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cms_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, 
      CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, CREATE VIEW, 
      SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE, TRIGGER, REFERENCES 
ON carpathian_cms.* TO 'cms_user'@'localhost';
FLUSH PRIVILEGES;
```

## 🚀 Post-Installation Commands

```bash
# 1. Fix permissions
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage bootstrap/cache

# 2. Visit install.php in browser
# http://your-domain.com/install.php

# 3. Delete install.php after completion
rm install.php
```

## 🔧 Common Installation Issues

### "Permission denied" errors
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### "500 Internal Server Error"
```bash
php artisan key:generate
php artisan storage:link
php artisan config:clear
```
