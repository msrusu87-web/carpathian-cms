# 🏔️ Carpathian CMS

A modern, multilingual Laravel-based Content Management System with e-commerce capabilities.

## ✨ Features

- **🌍 Multilingual Support**: Built-in support for Romanian, English, Italian, French, German, and Spanish
- **🛒 E-Commerce Plugin**: Full shop functionality with product management
- **📝 Widget System**: Flexible content widgets (hero, features, products, blog, documentation)
- **🎨 Modern UI**: Clean, responsive design
- **⚡ Laravel Framework**: Built on Laravel for performance and security
- **📦 WordPress-style Installation**: Easy guided installation wizard

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js & NPM (for frontend assets)
- Nginx or Apache web server

## 🚀 Quick Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/carpathian-cms.git
cd carpathian-cms
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3. Run the Installer

Navigate to `http://yourdomain.com/install.php` in your browser and follow the guided installation:

1. **Welcome** - Review requirements
2. **Database** - Enter database credentials
3. **Site Configuration** - Set site name, URL, and admin account
4. **Installation** - Complete the setup
5. **Done** - Visit your new website!

**Important**: Delete `install.php` after installation for security.

### 4. Manual Installation (Alternative)

If you prefer manual installation:

```bash
# Copy environment file
cp .env.example .env

# Edit .env with your database credentials
nano .env

# Generate application key
php artisan key:generate

# Import database
mysql -u your_user -p your_database < database/schema/carpathian_cms.sql

# Run migrations (if any)
php artisan migrate

# Create storage symlink
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan view:cache
```

## 🌐 Multilingual System

The CMS uses a WordPress-style translation system. Language files are located in `lang/[code]/messages.php`:

- `lang/ro/messages.php` - Romanian (default)
- `lang/en/messages.php` - English
- `lang/it/messages.php` - Italian
- `lang/fr/messages.php` - French
- `lang/de/messages.php` - German
- `lang/es/messages.php` - Spanish

### Adding New Languages

See `lang/TRANSLATION_GUIDE.md` for complete instructions on adding new languages.

Quick example:
```bash
# Copy a template
cp lang/en/messages.php lang/pt/messages.php

# Edit translations
nano lang/pt/messages.php
```

Change language in `.env`:
```env
APP_LOCALE=en  # or ro, it, fr, de, es
```

## 🎨 Theme Customization

- **Views**: `resources/views/frontend/`
- **Styles**: `resources/css/`
- **JavaScript**: `resources/js/`

After changes:
```bash
npm run build
php artisan view:clear
```

## 🛒 E-Commerce Features

The CMS includes a shop plugin with:
- Product management
- Shopping cart
- Order processing
- Web design services showcase

## 🔧 Configuration

### Database

Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

### Site Settings

```env
APP_NAME="Your Site Name"
APP_URL=https://yourdomain.com
APP_LOCALE=ro
```

## 📦 Production Deployment

### Ubuntu Server Setup

```bash
# Install dependencies
sudo apt update
sudo apt install -y php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl \
                    mysql-server nginx composer

# Clone and setup
cd /var/www
sudo git clone https://github.com/yourusername/carpathian-cms.git your-site
cd your-site
sudo chown -R www-data:www-data .
sudo chmod -R 755 storage bootstrap/cache

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Visit http://yourdomain.com/install.php
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/carpathian-cms/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Restart Nginx:
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm
```

## 🔐 Security

- Always delete `install.php` after installation
- Keep `.env` file secure (never commit to Git)
- Use strong database passwords
- Enable HTTPS in production
- Keep Laravel and dependencies updated

## 📝 Maintenance

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Update Dependencies
```bash
composer update
npm update && npm run build
```

### Backup Database
```bash
mysqldump -u user -p database > backup.sql
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-source and available under the MIT License.

## 🆘 Support

For issues and questions:
- Open an issue on GitHub
- Check `lang/TRANSLATION_GUIDE.md` for translation help
- Review Laravel documentation: https://laravel.com/docs

## 🙏 Credits

Built with:
- [Laravel](https://laravel.com) - PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Alpine.js](https://alpinejs.dev) - JavaScript Framework

---

Made with ❤️ for the Carpathian region

## 🤖 AI Content Generator

The CMS now includes powerful AI-powered content generation capabilities using Groq AI (Llama 3.3 70B).

### Features
- **Integrated in all major sections**: Products, Pages, Blog Posts, Widgets
- **Multi-field generation**: Generate multiple fields simultaneously
- **Multilingual support**: Works with all 6 languages (ro, en, de, fr, es, it)
- **Smart context awareness**: Use existing content as context for regeneration
- **Customizable tone & length**: Professional, persuasive, friendly, technical, casual
- **HTML formatting**: Automatic HTML generation for rich content fields
- **SEO optimization**: Generate SEO-friendly titles, descriptions, and keywords

### Quick Start

1. **Setup Groq API Key**

Add to your `.env` file:
```env
GROQ_API_KEY=your_groq_api_key_here
```

Get your free API key at: https://console.groq.com

2. **Access AI Generator**

**Option A - Standalone Page**:
- Navigate to: `Admin → AI → AI Content Writer`
- URL: https://yourdomain.com/admin/ai-content-writer
- Generate complete blog posts, pages, or product descriptions

**Option B - Integrated in Edit Pages**:
- Go to any Product/Page/Post/Widget edit screen
- Look for the ✨ **"Generate with AI"** button in the header
- Select fields to generate, add instructions, and click Generate

3. **Generate Content**

Example workflow for a product:
1. Edit product → Click "Generate with AI"
2. Select fields: `Description`, `SEO Title`, `SEO Keywords`
3. Instructions: "Write compelling description for project management software"
4. Tone: `Persuasive`
5. Length: `Medium`
6. Click "Generate"
7. Content will be generated and saved automatically

### Documentation

- **User Guide**: [AI_CONTENT_GENERATOR_GUIDE.md](AI_CONTENT_GENERATOR_GUIDE.md)
- **Technical Documentation**: [AI_CONTENT_GENERATOR_TECHNICAL.md](AI_CONTENT_GENERATOR_TECHNICAL.md)

### Supported Fields

#### Products
- Name, Description, Full Content
- SEO Title, SEO Description, SEO Keywords

#### Pages
- Title, Content
- SEO Title, SEO Description, SEO Keywords

#### Blog Posts
- Title, Excerpt, Content
- SEO Title, SEO Description, SEO Keywords

#### Widgets
- Title, Content
- Button 1 Text, Button 2 Text

### Benefits

✅ **Save Time**: Generate content in seconds instead of hours  
✅ **SEO Optimized**: AI follows SEO best practices  
✅ **Consistent Quality**: Professional tone across all content  
✅ **Multilingual**: Generate in 6 languages instantly  
✅ **Cost Effective**: Groq AI offers generous free tier  
✅ **No Vendor Lock-in**: Easy to switch AI providers if needed

### Requirements

- Groq API key (free at https://console.groq.com)
- Internet connection for API calls
- PHP 8.1+ with GuzzleHTTP

