# 🏔️ Carpathian CMS

**A modern, AI-powered Laravel 11 Content Management System with built-in e-commerce, marketing automation, and multilingual support.**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://php.net)
[![Filament](https://img.shields.io/badge/Filament-3.x-FDAE4B?style=flat-square)](https://filamentphp.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)

---

## ✨ Key Features

### 🤖 AI-Powered Content Generation
- **Groq AI Integration** (Llama 3.3 70B) - Generate SEO-optimized content for products, blog posts, and pages
- **Multi-field Generation** - Generate multiple content fields simultaneously
- **Automatic SEO Optimization** - AI generates meta titles, descriptions, and keywords
- **Multilingual AI Content** - Generate content in 6 languages with proper localization
- **Template & Plugin Generation** - AI can generate complete website templates and plugin code
- **Auto Blog Posts** - Generate blog posts based on your shop products automatically

### 🛒 Complete E-Commerce Suite
- **Product Management** - Categories, variants, inventory tracking, pricing
- **Multiple Payment Gateways**:
  - 💳 Stripe (Credit/Debit Cards)
  - 💰 PayPal (Classic & API)
  - 🏦 Bank Transfer
  - ₿ Cryptocurrency Wallet Integration
- **Order Management** - Full order lifecycle with status tracking
- **Coupon System** - Discount codes with flexible rules
- **Digital Products** - Support for downloadable products

### 📧 Marketing Automation (Plugin)
- **Email Harvesting** - Web scraping for business contact discovery
- **Contact Management** - Lists, tags, segmentation, GDPR compliance
- **Email Campaigns** - Rich HTML templates with TinyEditor
- **Brevo Integration** - Send campaigns via Brevo (Sendinblue) API
- **SMTP Support** - Native SMTP email sending
- **Anti-Spam Protection** - Rate limiting, unsubscribe handling, CAN-SPAM compliance
- **Google Places API** - Auto-complete company names, phones, and addresses

### 🌍 Multilingual System
- **6 Built-in Languages**: Romanian, English, German, French, Spanish, Italian
- **JSON Translation Files** - Easy to extend and maintain
- **Per-field Translations** - Each content field can have translations
- **AI Translation** - Generate content in multiple languages automatically
- **RTL Support** - Ready for right-to-left languages

### 🎨 Modern Admin Panel (Filament 3)
- **Cluster-based Navigation** - Organized menu structure:
  - 🛍️ Shop (Products, Categories, Orders, Coupons)
  - 📝 CMS (Pages, Posts, Widgets, Menus)
  - 📊 Marketing (Contacts, Campaigns, Scraper)
  - ⚙️ Settings (General, SEO, Email, Payments)
  - 🤖 AI (Content Writer, Generations, Settings)
- **Rich Text Editor** - TinyMCE integration for content editing
- **Media Manager** - Upload and manage images and files
- **Role-based Permissions** - Granular access control with Spatie Permissions

### 🔍 Advanced SEO Features
- **Meta Tags Management** - Title, description, keywords per page
- **Open Graph Tags** - Optimized social media sharing
- **Schema.org Markup** - Structured data for rich snippets
- **XML Sitemap** - Auto-generated sitemap
- **SEO Tools Integration** - artesaos/seotools package
- **AI SEO Suggestions** - AI-powered SEO recommendations

### 🔐 Security Features
- **Laravel Sanctum** - API token authentication
- **CSRF Protection** - Built-in cross-site request forgery protection
- **Encrypted Credentials** - Payment gateway credentials stored encrypted
- **Activity Logging** - Track all admin actions
- **Rate Limiting** - API and form submission rate limiting

### 📱 Responsive Design
- **Mobile-First** - Fully responsive frontend
- **PWA Ready** - Progressive Web App capabilities
- **Touch-Friendly** - Optimized for mobile devices

---

## 📋 Requirements

- **PHP** 8.2 or higher
- **MySQL** 8.0+ or MariaDB 10.6+
- **Composer** 2.x
- **Node.js** 18+ & NPM
- **Redis** (recommended for sessions/cache)

### PHP Extensions Required
```
BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL
```

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/msrusu87-web/carpathian-cms.git
cd carpathian-cms
```

### 2. Install Dependencies

```bash
composer install --optimize-autoloader
npm install && npm run build
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Environment

Edit `.env` with your settings:

```env
# Application
APP_NAME="Your Site Name"
APP_URL=https://yourdomain.com
APP_LOCALE=en

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=carpathian_cms
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Redis (Recommended)
REDIS_HOST=127.0.0.1

# AI Integration (Groq - Free & Fast)
GROQ_API_KEY=your_groq_api_key

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com

# Payment Gateways (Optional)
STRIPE_KEY=pk_live_xxx
STRIPE_SECRET=sk_live_xxx
PAYPAL_CLIENT_ID=xxx
PAYPAL_SECRET=xxx

# Marketing (Optional)
GOOGLE_PLACES_API_KEY=your_google_api_key
BREVO_API_KEY=your_brevo_api_key
```

### 5. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed default data (optional)
php artisan db:seed

# Create storage symlink
php artisan storage:link
```

### 6. Create Admin User

```bash
php artisan make:filament-user
```

### 7. Production Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
```

---

## 🎯 Quick Start

### Access Admin Panel
Navigate to `https://yourdomain.com/admin`

### Create Your First Content
1. **Products**: Admin → Shop → Products → Create New
2. **Blog Post**: Admin → Blog → Posts → Create New
3. **Page**: Admin → CMS → Pages → Create New

### Generate Content with AI
1. Edit any product, page, or post
2. Click "✨ Generate with AI" button
3. Select fields to generate
4. Add instructions and generate!

---

## 📁 Project Structure

```
carpathian-cms/
├── app/
│   ├── Filament/           # Admin panel resources
│   │   ├── Clusters/       # Navigation clusters
│   │   ├── Resources/      # CRUD resources
│   │   └── Widgets/        # Dashboard widgets
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic services
│   │   ├── AIService.php
│   │   ├── GroqAiService.php
│   │   └── ...
│   └── Http/               # Controllers & Middleware
├── config/                 # Configuration files
├── database/
│   ├── migrations/         # Database migrations
│   └── schema/             # Database schema (clean)
├── lang/                   # Translation files
│   ├── en/, ro/, de/, fr/, es/, it/
├── plugins/                # Plugin system
│   ├── marketing/          # Marketing automation plugin
│   └── freelancer/         # Freelancer marketplace plugin
├── public/                 # Public assets
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript
└── routes/                 # Route definitions
```

---

## 🔌 Plugin System

### Marketing Automation Plugin

Located in `plugins/marketing/`, includes:

- **Web Scraper** - Extract business contact info from websites
- **Contact Manager** - Store and segment contacts
- **Email Campaigns** - Create and send email campaigns
- **Brevo Integration** - Use Brevo API for campaign delivery
- **Analytics** - Track opens, clicks, and conversions

### Activating Plugins

```php
// In config or via Admin → Settings → Plugins
'plugins' => [
    'marketing' => true,
    'freelancer' => false,
]
```

---

## 🛠️ Configuration

### AI Settings

```env
# Groq (Recommended - Free tier available)
AI_PROVIDER=groq
GROQ_API_KEY=gsk_your_api_key
GROQ_MODEL=llama-3.3-70b-versatile

# OpenAI (Alternative)
OPENAI_API_KEY=sk-your_api_key
OPENAI_MODEL=gpt-4o
```

Get your free Groq API key: https://console.groq.com

### Payment Gateway Configuration

Configure via Admin → Settings → Shop Settings or in `.env`:

```env
# Stripe
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...

# PayPal
PAYPAL_MODE=live
PAYPAL_CLIENT_ID=...
PAYPAL_SECRET=...
```

### Email Marketing

```env
# SMTP (Direct sending)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com

# Brevo Integration (Recommended for campaigns)
BREVO_API_KEY=your_brevo_api_key
BREVO_USE_API=true
```

---

## 🚀 Deployment

### Ubuntu/Debian Server

```bash
# Install requirements
sudo apt update
sudo apt install php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
    php8.3-curl php8.3-redis mysql-server nginx redis-server

# Deploy
cd /var/www
git clone https://github.com/msrusu87-web/carpathian-cms.git your-site
cd your-site
composer install --optimize-autoloader --no-dev
npm ci && npm run build

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/your-site/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 📝 API Documentation

The CMS includes a REST API for headless usage:

- **Products API**: `/api/products`
- **Posts API**: `/api/posts`
- **Pages API**: `/api/pages`

API documentation available at: `/api/documentation` (Swagger/OpenAPI)

---

## 🔄 Maintenance

### Clear Caches

```bash
php artisan optimize:clear
```

### Update Dependencies

```bash
composer update
npm update && npm run build
```

### Backup Database

```bash
mysqldump -u user -p database > backup_$(date +%Y%m%d).sql
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for detailed guidelines.

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 🙏 Credits

Built with:
- [Laravel 11](https://laravel.com) - PHP Framework
- [Filament 3](https://filamentphp.com) - Admin Panel
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Groq AI](https://groq.com) - AI Content Generation
- [Alpine.js](https://alpinejs.dev) - JavaScript Framework
- [Livewire](https://livewire.laravel.com) - Full-stack Framework

---

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/msrusu87-web/carpathian-cms/issues)
- **Documentation**: [docs/](docs/)
- **Email**: msrusu87@gmail.com

---

**Made with ❤️ for modern web development**

