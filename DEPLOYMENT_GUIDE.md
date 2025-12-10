# 🚀 CMS Deployment & Usage Guide

## ✅ System Status

**ALL CHECKS PASSED** - System is ready for production!

## 🔑 Access Information

### Admin Panel
- **URL**: https://cms.carphatian.ro/admin
- **Email**: msrusu87@gmail.com
- **Password**: Maria1940!!!

### Public Website
- **URL**: https://cms.carphatian.ro

### Database
- **Database**: modularcms
- **User**: root
- **Password**: Maria1940!!!

### API Credentials
- **Groq API Key**: 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

---

## 🎯 Quick Start Guide

### 1. Access Admin Panel
1. Visit: https://cms.carphatian.ro/admin
2. Login with credentials above
3. You'll see the Filament dashboard

### 2. Create Your First Content

#### Create a Category
1. Go to **Categories** in sidebar
2. Click **New Category**
3. Fill in:
   - Name: "Technology"
   - Slug: "technology" (auto-generated)
   - Description: Optional
4. Click **Create**

#### Create a Blog Post
1. Go to **Posts** in sidebar
2. Click **New Post**
3. Fill in:
   - Title: "Welcome to Our AI-Powered CMS"
   - Slug: "welcome-ai-cms" (auto-generated)
   - Content: Your post content (supports HTML)
   - Excerpt: Brief summary
   - Status: Published
   - Category: Select "Technology"
   - Featured Image: Upload via Media Library
4. Click **Create**

#### Create a Page
1. Go to **Pages** in sidebar
2. Click **New Page**
3. Fill in:
   - Title: "About Us"
   - Slug: "about"
   - Content: Your page content
   - Template: Select "Default Theme"
   - Set as Homepage: Toggle if needed
4. Click **Create**

---

## 🤖 AI Features Usage

### Generate a Custom Template with AI

1. **Via API** (Postman, cURL, or JavaScript):

```bash
curl -X POST https://cms.carphatian.ro/api/ai/templates/generate \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_SANCTUM_TOKEN" \
  -d '{
    "prompt": "Create a modern blog template with dark mode, large hero section, and card-based post grid",
    "name": "Modern Dark Blog",
    "type": "blog"
  }'
```

2. **Response**: You'll get a complete template with HTML, CSS, and layouts

### Generate a Plugin with AI

```bash
curl -X POST https://cms.carphatian.ro/api/ai/plugins/generate \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_SANCTUM_TOKEN" \
  -d '{
    "prompt": "Create a plugin that adds social sharing buttons to all posts",
    "name": "Social Share Pro"
  }'
```

### Generate Template Blocks

```bash
curl -X POST https://cms.carphatian.ro/api/ai/templates/blocks/generate \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_SANCTUM_TOKEN" \
  -d '{
    "template_id": 1,
    "prompt": "Create a newsletter signup section with email input and subscribe button",
    "name": "Newsletter Block"
  }'
```

---

## 🛠️ Maintenance Commands

### Validation Script
Run before any major deployment:
```bash
cd /var/www/cms.carphatian.ro
sudo -u www-data php validate.php
```

### Clear Caches
```bash
cd /var/www/cms.carphatian.ro
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
```

### Rebuild Caches (Production)
```bash
cd /var/www/cms.carphatian.ro
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

### Generate Sitemap
```bash
cd /var/www/cms.carphatian.ro
sudo -u www-data php artisan sitemap:generate
```

### Optimize Performance
```bash
cd /var/www/cms.carphatian.ro
sudo -u www-data php artisan optimize
```

---

## 📊 Database Management

### Backup Database
```bash
mysqldump -u root -p modularcms > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restore Database
```bash
mysql -u root -p modularcms < backup_file.sql
```

### Check Tables
```bash
mysql -u root -p modularcms -e "SHOW TABLES;"
```

---

## 🔌 API Endpoints

All AI endpoints require authentication via Sanctum tokens.

### Template Generation
- **POST** `/api/ai/templates/generate`
- **POST** `/api/ai/templates/blocks/generate`

### Plugin Generation
- **POST** `/api/ai/plugins/generate`
- **POST** `/api/ai/plugins/{id}/improve`

### Authentication (Generate Token)
```bash
# In Tinker
php artisan tinker
$user = User::first();
$token = $user->createToken('api-token')->plainTextToken;
echo $token;
```

---

## 🎨 Template System

### Available Variables

Templates use `{{ variable }}` syntax:

**Global Variables:**
- `{{ title }}` - Page/Post title
- `{{ content }}` - Main content HTML
- `{{ meta_title }}` - SEO title
- `{{ meta_description }}` - SEO description
- `{{ site_name }}` - Site name from settings

**Post Variables:**
- `{{ author.name }}` - Author name
- `{{ category.name }}` - Category name
- `{{ published_at }}` - Publication date

**Page Variables:**
- `{{ content_html }}` - Rendered content
- `{{ is_homepage }}` - Boolean flag

### Template Layouts

Each template can have multiple layouts:
- `default` - Standard pages
- `home` - Homepage layout
- `post` - Blog post layout
- `page` - Static page layout

---

## 🔧 Troubleshooting

### Frontend Shows Error

1. **Check Template**:
```bash
cd /var/www/cms.carphatian.ro
sudo -u www-data php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
\$template = App\Models\Template::active()->first();
echo \$template ? 'Template: ' . \$template->name : 'No active template';
"
```

2. **Run Validation**:
```bash
sudo -u www-data php validate.php
```

3. **Check Logs**:
```bash
tail -50 storage/logs/laravel.log
```

### AI Generation Not Working

1. **Verify API Key**:
```bash
cd /var/www/cms.carphatian.ro
grep GROQ_API_KEY .env
```

2. **Test Connection**:
```bash
curl -X POST https://api.groq.com/openai/v1/chat/completions \
  -H "Authorization: Bearer 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" \
  -H "Content-Type: application/json" \
  -d '{"model":"llama-3.3-70b-versatile","messages":[{"role":"user","content":"Hello"}]}'
```

### Permission Issues

```bash
cd /var/www/cms.carphatian.ro
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## 📈 Performance Optimization

### Enable OPcache (if not enabled)
Add to php.ini:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

### Database Indexing
All critical fields already indexed in migrations.

### CDN Integration
Templates use Tailwind CDN. For production, compile assets:
```bash
npm install
npm run build
```

---

## 🔐 Security Checklist

- ✅ HTTPS enabled (Let's Encrypt)
- ✅ Strong passwords set
- ✅ API authentication via Sanctum
- ✅ CSRF protection enabled
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade escaping)
- ⚠️ **TODO**: Set up regular database backups
- ⚠️ **TODO**: Configure firewall rules (UFW)
- ⚠️ **TODO**: Set up monitoring (New Relic, Sentry)

---

## 📚 Additional Resources

### Documentation Files
- `/var/www/cms.carphatian.ro/README_CMS.md` - Complete technical docs
- `/var/www/cms.carphatian.ro/validate.php` - System validation script

### Filament Docs
- https://filamentphp.com/docs

### Groq AI Docs
- https://console.groq.com/docs

### Laravel Docs
- https://laravel.com/docs/11.x

---

## 🎉 Next Steps

1. **Create sample content** via Admin Panel
2. **Test AI template generation** via API
3. **Customize default template** in Templates section
4. **Add custom plugins** using AI generator
5. **Configure SEO settings** in Settings panel
6. **Set up email notifications** (SMTP in .env)
7. **Add Google Analytics** via Settings
8. **Create user roles** for team members

---

## 📞 Support

For technical issues or questions:
- Check logs: `storage/logs/laravel.log`
- Run validation: `php validate.php`
- Review documentation: `README_CMS.md`

**System Version**: 1.0.0
**Last Updated**: December 3, 2025
**Status**: ✅ Production Ready
