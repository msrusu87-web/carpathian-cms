# Carphatian CMS - Installation Guide

A Laravel 11 + Filament 3 CMS with multi-language support, e-commerce, and AI content generation.

## Requirements

- **PHP**: 8.2+ (tested with 8.4)
- **MySQL**: 8.0+
- **Nginx** or Apache
- **Composer**: 2.x
- **Node.js**: 18+ (for asset building)
- **NPM**: 9+

### Required PHP Extensions
```bash
php -m | grep -E "bcmath|ctype|curl|dom|fileinfo|gd|intl|json|mbstring|openssl|pdo|pdo_mysql|tokenizer|xml|zip"
```

Install missing extensions (Ubuntu/Debian):
```bash
sudo apt install php8.4-{bcmath,ctype,curl,dom,fileinfo,gd,intl,json,mbstring,openssl,pdo,pdo_mysql,tokenizer,xml,zip,fpm}
```

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/msrusu87-web/carpathian-cms.git
cd carpathian-cms
```

### 2. Install PHP Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your settings:
```env
APP_URL=https://yourdomain.com
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
SESSION_DOMAIN=.yourdomain.com
```

### 4. Create Database
```bash
mysql -u root -p
```
```sql
CREATE DATABASE carphatian_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'carphatian'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON carphatian_cms.* TO 'carphatian'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Import Database Schema
```bash
mysql -u carphatian -p carphatian_cms < database/schema.sql
```

Or run migrations:
```bash
php artisan migrate
```

### 6. Run Seeders (Optional)
```bash
php artisan db:seed --class=RoleAndPermissionSeeder
php artisan db:seed --class=MenuSeeder
php artisan db:seed --class=WidgetSeeder
php artisan db:seed --class=EmailTemplatesSeeder
```

### 7. Create Admin User
```bash
php artisan make:filament-user
```
Or via tinker:
```bash
php artisan tinker
```
```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@yourdomain.com',
    'password' => bcrypt('your_secure_password'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);
```

### 8. Build Assets
```bash
npm install
npm run build
```

### 9. Create Storage Link
```bash
php artisan storage:link
```

### 10. Clear Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## File & Directory Permissions

### Directory Permissions
```bash
# Set ownership (replace www-data with your web server user)
sudo chown -R www-data:www-data /var/www/yourdomain

# Directories should be 775
find /var/www/yourdomain -type d -exec chmod 775 {} \;

# Files should be 664
find /var/www/yourdomain -type f -exec chmod 664 {} \;
```

### Critical Writable Directories
```bash
# Storage must be fully writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Public storage
chmod -R 775 public/storage

# Framework caches
chmod -R 775 storage/framework/cache
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/framework/views
chmod -R 775 storage/logs
```

### Permission Summary Table

| Path | Permission | Purpose |
|------|------------|---------|
| `/` (root) | 775 | Project root |
| `storage/` | 775 | All storage |
| `storage/logs/` | 775 | Log files |
| `storage/framework/cache/` | 775 | Cache files |
| `storage/framework/sessions/` | 775 | Session files |
| `storage/framework/views/` | 775 | Compiled views |
| `bootstrap/cache/` | 775 | Config cache |
| `public/storage/` | 775 | Public uploads |
| `*.php` files | 664 | PHP code (644 also acceptable) |
| `.env` | 640 | Environment (restricted) |

## Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    
    root /var/www/yourdomain/public;
    index index.php index.html;
    
    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    
    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml text/javascript;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    # Static assets caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
    
    client_max_body_size 100M;
}
```

## Queue Worker (Optional)

For background jobs (emails, AI generation):

```bash
# Create systemd service
sudo nano /etc/systemd/system/carphatian-worker.service
```

```ini
[Unit]
Description=Carphatian Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/yourdomain/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload
sudo systemctl enable carphatian-worker
sudo systemctl start carphatian-worker
```

## Cron Job (Scheduler)

```bash
sudo crontab -e -u www-data
```

Add:
```cron
* * * * * cd /var/www/yourdomain && php artisan schedule:run >> /dev/null 2>&1
```

## AI Configuration (Optional)

For AI content generation, add API keys to `.env`:

### Groq (Free tier available)
```env
GROQ_API_KEY=gsk_your_api_key_here
GROQ_MODEL=llama-3.3-70b-versatile
```

### OpenAI
```env
OPENAI_API_KEY=sk-your_api_key_here
OPENAI_MODEL=gpt-4-turbo-preview
```

## Stripe Payments (Optional)

```env
STRIPE_KEY=pk_live_your_key
STRIPE_SECRET=sk_live_your_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

## Troubleshooting

### 500 Error
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
tail -50 storage/logs/laravel.log
```

### Permission Denied
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Session/Login Issues
- Ensure `SESSION_DOMAIN` matches your domain (include dot prefix: `.yourdomain.com`)
- Ensure `SESSION_SECURE_COOKIE=true` for HTTPS
- Clear browser cookies

### Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

## Features

- 🌍 Multi-language support (EN, RO, DE, FR, ES, IT)
- 🛒 E-commerce with cart & checkout
- 🤖 AI content generation (Groq/OpenAI)
- 📝 Blog with categories & tags
- 📄 Dynamic pages with SEO
- 👥 Role-based permissions
- 💬 Live chat support
- 📊 Analytics dashboard
- 📧 Email templates
- 🔒 Two-factor authentication

## Admin Panel

Access the admin panel at: `https://yourdomain.com/admin`

## License

MIT License - See LICENSE file for details.
