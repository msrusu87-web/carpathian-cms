# ⚙️ Configuration Guide

Complete guide to configuring Carpathian CMS.

## Table of Contents

1. [Environment Configuration](#environment-configuration)
2. [Database Settings](#database-settings)
3. [AI Integration](#ai-integration)
4. [Payment Gateways](#payment-gateways)
5. [Email Configuration](#email-configuration)
6. [Storage & Media](#storage--media)
7. [Multilingual Settings](#multilingual-settings)
8. [Performance Optimization](#performance-optimization)
9. [Security Settings](#security-settings)

---

## Environment Configuration

### Basic `.env` Settings

```bash
# Application
APP_NAME="Carpathian CMS"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your domain.com

# Timezone and Locale
APP_TIMEZONE=UTC
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
```

### Generate Application Key

```bash
php artisan key:generate
```

---

## Database Settings

### MySQL Configuration

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carpathian_cms
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

### Database Optimization

```bash
# In my.cnf or my.ini
[mysqld]
innodb_buffer_pool_size = 1G
max_connections = 200
query_cache_size = 64M
```

### Run Migrations

```bash
# Fresh installation
php artisan migrate --seed

# With specific seeder
php artisan db:seed --class=DatabaseSeeder
```

---

## AI Integration

### Groq API (Recommended - Fast & Free)

```bash
GROQ_API_KEY=gsk_your_groq_api_key_here
GROQ_MODEL=llama-3.1-70b-versatile
GROQ_MAX_TOKENS=8000
```

**Get your key:** [https://console.groq.com/keys](https://console.groq.com/keys)

### OpenAI API (Alternative)

```bash
OPENAI_API_KEY=sk-your_openai_key_here
OPENAI_MODEL=gpt-4o
OPENAI_MAX_TOKENS=4000
```

**Get your key:** [https://platform.openai.com/api-keys](https://platform.openai.com/api-keys)

### AI Service Configuration

```bash
# Choose your AI provider
AI_PROVIDER=groq  # Options: groq, openai, custom

# Custom AI Service (FastAPI)
AI_SERVICE_URL=http://localhost:8001
AI_SERVICE_TIMEOUT=30
```

### Testing AI Connection

```bash
php artisan ai:test
```

---

## Payment Gateways

### Stripe Configuration

```bash
STRIPE_KEY=pk_live_your_stripe_public_key
STRIPE_SECRET=sk_live_your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

**Setup Webhooks:**

1. Go to [Stripe Dashboard → Webhooks](https://dashboard.stripe.com/webhooks)
2. Add endpoint: `https://yourdomain.com/stripe/webhook`
3. Select events:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `charge.refunded`

### PayPal Configuration

```bash
PAYPAL_MODE=live  # Options: sandbox, live
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_SECRET=your_paypal_secret
```

**Get credentials:** [PayPal Developer Dashboard](https://developer.paypal.com/dashboard/)

---

## Email Configuration

### SMTP Settings (Recommended)

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Gmail Setup

1. Enable 2-Factor Authentication
2. Generate App Password
3. Use app password in `MAIL_PASSWORD`

### Amazon SES

```bash
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
```

### Mailgun

```bash
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.yourdomain.com
MAILGUN_SECRET=your_mailgun_secret
```

### Test Email

```bash
php artisan mail:test your-email@example.com
```

---

## Storage & Media

### Local Storage

```bash
FILESYSTEM_DISK=local
```

### Amazon S3

```bash
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com
```

### DigitalOcean Spaces

```bash
FILESYSTEM_DISK=spaces
DO_SPACES_KEY=your_spaces_key
DO_SPACES_SECRET=your_spaces_secret
DO_SPACES_ENDPOINT=https://nyc3.digitaloceanspaces.com
DO_SPACES_REGION=nyc3
DO_SPACES_BUCKET=your-bucket-name
```

### Media Library Settings

```bash
MEDIA_DISK=public
MAX_UPLOAD_SIZE=10240  # KB (10MB)
ALLOWED_EXTENSIONS=jpg,jpeg,png,gif,pdf,doc,docx
```

### Create Storage Link

```bash
php artisan storage:link
```

---

## Multilingual Settings

### Supported Languages

```bash
# Available languages (comma-separated)
LANGUAGES=en,ro,es

# Default language
DEFAULT_LANGUAGE=en

# Fallback language
FALLBACK_LANGUAGE=en
```

### Add New Language

1. **Add to `.env`:**
   ```bash
   LANGUAGES=en,ro,es,de  # Added German
   ```

2. **Create language file:**
   ```bash
   cp -r lang/en lang/de
   ```

3. **Update translations:**
   Edit files in `lang/de/`

4. **Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

### SEO per Language

Configure in Filament admin:
- Settings → SEO → Language-specific meta tags
- Pages → Edit → Language selector

---

## Performance Optimization

### Cache Configuration

```bash
CACHE_DRIVER=redis  # Options: file, redis, memcached
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

### Redis Setup

```bash
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
```

### Queue Workers

```bash
# Start queue worker
php artisan queue:work --daemon

# Supervisor configuration (recommended)
sudo nano /etc/supervisor/conf.d/carpathian-worker.conf
```

**Supervisor config:**

```ini
[program:carpathian-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/carphatian.ro/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/carphatian.ro/html/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start carpathian-worker:*
```

### Optimize Application

```bash
# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear cache when needed
php artisan optimize:clear
```

---

## Security Settings

### Application Security

```bash
# Session configuration
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# HTTPS enforcement
FORCE_HTTPS=true
```

### Rate Limiting

Edit `config/app.php`:

```php
'rate_limiters' => [
    'api' => [
        'provider' => 'throttle:api',
        'max_attempts' => 60,
        'decay_minutes' => 1,
    ],
    'login' => [
        'provider' => 'throttle:login',
        'max_attempts' => 5,
        'decay_minutes' => 15,
    ],
],
```

### Security Headers (Nginx)

Add to nginx config:

```nginx
add_header X-Frame-Options "SAMEORIGIN";
add_header X-Content-Type-Options "nosniff";
add_header X-XSS-Protection "1; mode=block";
add_header Referrer-Policy "strict-origin-when-cross-origin";
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';";
```

### CORS Configuration

Edit `config/cors.php`:

```php
'allowed_origins' => ['https://yourdomain.com'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_headers' => ['*'],
'max_age' => 86400,
```

---

## Filament Admin Panel

### Admin Settings

```bash
FILAMENT_PATH=admin
FILAMENT_DOMAIN=null
```

### Custom Admin URL

Change admin path (security through obscurity):

```bash
FILAMENT_PATH=secure-admin-2024
```

Access at: `https://yourdomain.com/secure-admin-2024`

### Admin Theme

Customize colors in `config/filament.php`:

```php
'theme' => [
    'colors' => [
        'primary' => '#3b82f6',  // Blue
        'secondary' => '#64748b',  // Slate
        'success' => '#22c55e',  // Green
        'danger' => '#ef4444',  // Red
        'warning' => '#f59e0b',  // Amber
    ],
],
```

---

## Cron Jobs

Add to crontab:

```bash
crontab -e
```

Add this line:

```bash
* * * * * cd /var/www/carphatian.ro/html && php artisan schedule:run >> /dev/null 2>&1
```

This runs:
- Cache cleanup
- Log rotation
- Sitemap generation
- Backup tasks

---

## Debugging & Logs

### Enable Debug Mode (Development Only)

```bash
APP_DEBUG=true
APP_ENV=local
LOG_LEVEL=debug
```

### View Logs

```bash
# Real-time log viewing
tail -f storage/logs/laravel.log

# Last 100 lines
tail -n 100 storage/logs/laravel.log
```

### Log Channels

```bash
LOG_CHANNEL=stack  # Options: stack, single, daily, slack
```

### Clear Logs

```bash
> storage/logs/laravel.log
```

---

## Backup Configuration

### Database Backups

```bash
# Manual backup
php artisan backup:run

# Automated (via cron)
php artisan backup:run --only-db
```

### Backup to S3

Edit `config/backup.php`:

```php
'destination' => [
    'disks' => ['s3'],
],
```

---

## Environment-Specific Settings

### Development

```bash
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Staging

```bash
APP_ENV=staging
APP_DEBUG=false
LOG_LEVEL=info
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Production

```bash
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_SECURE_COOKIE=true
FORCE_HTTPS=true
```

---

## Configuration Checklist

After configuration, verify:

- [ ] Database connection works
- [ ] Email sending functional
- [ ] AI integration active
- [ ] Payment gateways configured
- [ ] Storage accessible
- [ ] Cron jobs running
- [ ] Queue workers active
- [ ] Cache operational
- [ ] Security headers set
- [ ] Backups scheduled

### Quick Test

```bash
php artisan config:show
php artisan about
```

---

## Troubleshooting

### Configuration Cached

If changes don't apply:

```bash
php artisan config:clear
php artisan cache:clear
```

### Permission Issues

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Cannot Write to .env

```bash
sudo chmod 664 .env
```

---

## Next Steps

- [📝 Customization Guide](CUSTOMIZATION.md)
- [🤖 AI Integration Setup](AI_INTEGRATION.md)
- [🌐 Multilingual Configuration](MULTILINGUAL.md)
- [🛒 E-Commerce Setup](ECOMMERCE.md)

---

**Need help?** Visit [carphatian.ro](https://carphatian.ro) or [open an issue](https://github.com/msrusu87-web/carpathian-cms/issues).
