# ModularCMS - AI-Powered Content Management System

## 🚀 Next-Generation CMS with Groq AI Integration

ModularCMS is a revolutionary content management system that combines the power of Laravel, Filament, and Groq AI to create an unparalleled content creation and management experience. Think WordPress, but supercharged with AI capabilities.

---

## 🎯 Key Features

### AI-Powered Theme Generation
- **Groq AI Integration**: Generate complete, production-ready themes using natural language
- **Custom Template Engine**: Support for multiple templates with dynamic switching
- **Block-Based Design**: Create and customize template blocks (header, footer, hero, etc.)
- **Real-time Preview**: See your AI-generated themes instantly

### Dynamic Plugin System
- **AI Plugin Generation**: Describe your plugin needs, AI creates the code
- **Hook System**: WordPress-style hooks and filters
- **Plugin Marketplace Ready**: Install, activate, deactivate plugins easily
- **Code Improvement**: AI can refactor and improve existing plugin code

### Advanced Content Management
- **Pages & Posts**: Full-featured content creation with SEO optimization
- **Categories & Tags**: Organize content hierarchically
- **Media Library**: Advanced media management with metadata
- **Custom Fields**: Flexible content structure
- **Draft/Publish Workflow**: Schedule and manage content lifecycle

### Beautiful Admin Interface (Filament 3)
- **Modern UI**: Clean, intuitive interface built with Filament
- **Resource Management**: CRUD operations for all content types
- **Dashboard Analytics**: Track views, engagement, and performance
- **User Roles**: Fine-grained permission control

### SEO & Performance
- **Auto-Generated Sitemaps**: XML sitemaps for search engines
- **Meta Tag Management**: Full control over SEO metadata
- **Caching System**: Built-in caching for optimal performance
- **Image Optimization**: Automatic image processing
- **Fast Loading**: Optimized for Core Web Vitals

---

## 📊 Database Structure

### Core Tables
- **users**: Admin and author management
- **categories**: Hierarchical content organization
- **tags**: Content tagging system
- **posts**: Blog posts with full metadata
- **pages**: Static pages with custom layouts
- **media**: File and image management

### AI & Template System
- **templates**: Theme/template storage with AI generation tracking
- **template_blocks**: Individual template components
- **plugins**: Dynamic plugin system with code storage
- **ai_generations**: Track all AI-generated content
- **settings**: Global CMS configuration

---

## 🔧 Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Admin Panel**: Filament 3
- **Database**: MySQL
- **AI Engine**: Groq AI (Llama 3.3 70B)
- **Frontend**: Blade Templates + Tailwind CSS
- **Caching**: Redis-ready
- **Queue System**: Laravel Queues

---

## 🚦 Quick Start

### Access Points

**Admin Panel**: https://cms.carphatian.ro/admin
- Email: msrusu87@gmail.com  
- Password: Maria1940!!!

**Public Site**: https://cms.carphatian.ro

### API Endpoints

```
POST /api/ai/templates/generate
POST /api/ai/templates/blocks/generate
POST /api/ai/plugins/generate
POST /api/ai/plugins/{id}/improve
```

---

## 💡 AI Features Usage

### Generate a Theme

1. Navigate to **Templates** in admin panel
2. Click **Generate with AI**
3. Describe your theme:
   ```
   Create a modern e-commerce theme with:
   - Hero section with call-to-action
   - Product grid layout
   - Shopping cart integration
   - Mobile-first responsive design
   - Blue and white color scheme
   ```
4. AI generates complete HTML, CSS, and JavaScript
5. Preview and activate your theme

### Generate a Plugin

1. Go to **Plugins** section
2. Click **Create Plugin with AI**
3. Describe functionality:
   ```
   Create a contact form plugin that:
   - Validates email and phone
   - Sends notifications to admin
   - Stores submissions in database
   - Has spam protection
   ```
4. AI generates the complete plugin code
5. Activate and configure

---

## 📁 Project Structure

```
cms.carphatian.ro/
├── app/
│   ├── Models/              # Eloquent models
│   │   ├── Page.php
│   │   ├── Post.php
│   │   ├── Template.php
│   │   ├── Plugin.php
│   │   └── AiGeneration.php
│   ├── Services/
│   │   ├── GroqAiService.php
│   │   └── TemplateRendererService.php
│   ├── Http/Controllers/
│   │   ├── FrontendController.php
│   │   └── Api/
│   │       ├── AiTemplateController.php
│   │       └── AiPluginController.php
│   └── Filament/Resources/  # Admin resources
├── database/
│   └── migrations/          # All database migrations
├── resources/
│   └── views/
│       └── frontend/        # Public-facing views
└── routes/
    ├── web.php              # Frontend routes
    └── api.php              # API routes
```

---

## 🎨 Template System

### How Templates Work

1. **Creation**: Templates can be created manually or via AI
2. **Structure**: Each template has layouts (header, footer, main)
3. **Blocks**: Templates are composed of reusable blocks
4. **Rendering**: TemplateRendererService dynamically renders pages
5. **Caching**: Rendered templates are cached for performance

### Template Variables

Use these in your templates:
- `{{title}}` - Page/Post title
- `{{content}}` - Main content
- `{{meta_title}}` - SEO title
- `{{meta_description}}` - SEO description
- `{{category}}` - Post category
- `{{tags}}` - Post tags

---

## 🔌 Plugin System

### Plugin Structure

```php
[
    'name' => 'My Plugin',
    'slug' => 'my-plugin',
    'code' => '<?php function my_hook($data) { ... } ?>',
    'hooks' => [
        'before_render' => 'my_hook',
        'after_render' => 'another_hook'
    ]
]
```

### Available Hooks

- `before_render`: Modify content before rendering
- `after_render`: Modify final HTML output
- `before_save`: Modify content before database save
- `after_save`: Trigger actions after save

---

## 🔒 Security Features

- CSRF Protection (Laravel built-in)
- SQL Injection Prevention (Eloquent ORM)
- XSS Protection (Blade escaping)
- Authentication via Filament
- API Rate Limiting
- Secure Password Hashing

---

## 📈 Performance Optimization

### Caching Strategy
- Template caching (1 hour TTL)
- Query result caching
- Settings caching
- Route caching

### Optimization Commands
```bash
php artisan optimize          # Optimize application
php artisan view:cache        # Cache blade views
php artisan route:cache       # Cache routes
php artisan config:cache      # Cache configuration
```

---

## 🌐 API Documentation

### Generate Template

**Endpoint**: `POST /api/ai/templates/generate`

**Headers**: 
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body**:
```json
{
    "name": "Modern Blog Theme",
    "description": "Create a clean, modern blog theme",
    "style": "modern",
    "color_scheme": "blue",
    "features": ["responsive", "dark-mode", "sidebar"]
}
```

**Response**:
```json
{
    "success": true,
    "template": { ... },
    "message": "Template generated successfully!"
}
```

---

## 🔮 Groq AI Configuration

Add to `.env`:
```
GROQ_API_KEY=your_groq_api_key_here
```

Get your API key from: https://console.groq.com/

---

## 🚀 Deployment

1. **Set permissions**:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data .
   ```

2. **Generate app key**:
   ```bash
   php artisan key:generate
   ```

3. **Run migrations**:
   ```bash
   php artisan migrate
   ```

4. **Optimize**:
   ```bash
   php artisan optimize
   php artisan filament:optimize
   ```

5. **Generate sitemap**:
   ```bash
   php artisan sitemap:generate
   ```

---

## 📚 Advanced Features

### Custom Content Types
Extend the CMS by creating new models and Filament resources:
```bash
php artisan make:model Product -m
php artisan make:filament-resource Product --generate
```

### Webhooks Integration
Trigger webhooks on content publish, plugin activation, etc.

### Multi-language Support
Built-in support for multiple languages (future feature)

### Backup System
Automated backups via Laravel Backup package (install separately)

---

## 🎓 Learning Resources

- Laravel Docs: https://laravel.com/docs
- Filament Docs: https://filamentphp.com/docs
- Groq AI Docs: https://console.groq.com/docs

---

## 🤝 Contributing

This is a custom CMS built for specific needs. For feature requests or bug reports, contact the development team.

---

## 📄 License

Proprietary - All Rights Reserved

---

## 🏆 What Makes This Special

Unlike WordPress:
- ✅ **AI-Native**: Built from ground up with AI integration
- ✅ **Modern Stack**: Laravel 11 + Filament 3
- ✅ **Type-Safe**: PHP 8.2+ with strict types
- ✅ **Fast**: Optimized caching and query performance
- ✅ **Secure**: Latest security practices built-in
- ✅ **Developer-Friendly**: Clean, maintainable codebase
- ✅ **Extensible**: Plugin and template systems
- ✅ **AI-Powered**: Generate themes and plugins with natural language

---

## 📞 Support

For technical support, contact: msrusu87@gmail.com

**CMS Version**: 1.0.0  
**Last Updated**: December 3, 2025  
**Status**: Production Ready ✅
