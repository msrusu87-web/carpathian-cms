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
- **Google Places API** - Auto-complete company names, phones, and addresses

### 🌍 Multilingual System
- **6 Built-in Languages**: Romanian, English, German, French, Spanish, Italian
- **JSON Translation Files** - Easy to extend and maintain
- **Per-field Translations** - Each content field can have translations
- **AI Translation** - Generate content in multiple languages automatically

### 🎨 Modern Admin Panel (Filament 3)
- **Cluster-based Navigation** - Organized menu structure:
  - 🛍️ Shop (Products, Categories, Orders, Coupons)
  - 📝 CMS (Pages, Posts, Widgets, Menus)
  - 📊 Marketing (Contacts, Campaigns, Scraper)
  - ⚙️ Settings (General, SEO, Email, Payments)
  - 🤖 AI (Content Writer, Generations, Settings)
- **Rich Text Editor** - TinyMCE integration for content editing
- **Media Manager** - Upload and manage images and files
- **Role-based Permissions** - Granular access control

### 🔍 Advanced SEO Features
- **Meta Tags Management** - Title, description, keywords per page
- **Open Graph Tags** - Optimized social media sharing
- **Schema.org Markup** - Structured data for rich snippets
- **XML Sitemap** - Auto-generated sitemap
- **AI SEO Suggestions** - AI-powered SEO recommendations

---

## 📋 Requirements

- **PHP** 8.2 or higher
- **MySQL** 8.0+ or MariaDB 10.6+
- **Composer** 2.x
- **Node.js** 18+ & NPM
- **Redis** (recommended for sessions/cache)

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

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=carpathian_cms
DB_USERNAME=your_user
DB_PASSWORD=your_password

# AI Integration (Groq - Free & Fast)
GROQ_API_KEY=your_groq_api_key

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587

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
php artisan migrate
php artisan db:seed
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

## 📁 Project Structure

```
carpathian-cms/
├── app/
│   ├── Filament/           # Admin panel resources
│   │   ├── Clusters/       # Navigation clusters
│   │   └── Resources/      # CRUD resources
│   ├── Models/             # Eloquent models
│   └── Services/           # Business logic (AI, Payments)
├── config/                 # Configuration files
├── database/migrations/    # Database migrations
├── lang/                   # Translation files (en, ro, de, fr, es, it)
├── plugins/                # Plugin system
│   └── marketing/          # Marketing automation plugin
├── resources/views/        # Blade templates
└── routes/                 # Route definitions
```

---

## 🔌 Marketing Plugin

Located in `plugins/marketing/`:

- **Web Scraper** - Extract business contact info from websites
- **Contact Manager** - Store and segment contacts
- **Email Campaigns** - Create and send campaigns
- **Brevo Integration** - Send via Brevo API
- **Google Places** - Company name/phone autocomplete

---

## 🛠️ Configuration

### AI Settings

```env
AI_PROVIDER=groq
GROQ_API_KEY=gsk_your_api_key
GROQ_MODEL=llama-3.3-70b-versatile
```

Get your free Groq API key: https://console.groq.com

---

## 🚀 Deployment

### Ubuntu Server

```bash
sudo apt install php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl mysql-server nginx

cd /var/www
git clone https://github.com/msrusu87-web/carpathian-cms.git your-site
cd your-site
composer install --optimize-autoloader --no-dev
npm ci && npm run build

sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

**Made with ❤️ for modern web development**
