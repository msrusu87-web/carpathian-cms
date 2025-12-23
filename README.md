# 🏔️ Carpathian CMS

<div align="center">

![Carpathian CMS](https://carphatian.ro/assets/logo.svg)

**A Modern, Multilingual CMS with AI Integration, E-Commerce & Freelance Marketplace**

[![Live Demo](https://img.shields.io/badge/demo-carphatian.ro-blue?style=for-the-badge&logo=laravel)](https://carphatian.ro)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE)

[🌐 Live Demo](https://carphatian.ro) • [📚 Documentation](docs/INSTALLATION.md) • [🚀 Quick Start](#-quick-start) • [💬 Support](#-support)

</div>

---

## ✨ Features at a Glance

<table>
  <tr>
    <td align="center" width="25%">
      <h3>🤖 AI-Powered</h3>
      ✓ AI Content Generator<br/>
      ✓ Smart SEO Optimization<br/>
      ✓ Intelligent Chat Support<br/>
      ✓ Auto-translations
    </td>
    <td align="center" width="25%">
      <h3>🌍 Multilingual</h3>
      ✓ 3 Languages (EN, RO, ES)<br/>
      ✓ RTL Support Ready<br/>
      ✓ SEO per Language<br/>
      ✓ Translation Manager
    </td>
    <td align="center" width="25%">
      <h3>🛒 E-Commerce</h3>
      ✓ Product Management<br/>
      ✓ Order Processing<br/>
      ✓ Payment Gateways<br/>
      ✓ Inventory Tracking
    </td>
    <td align="center" width="25%">
      <h3>💼 Freelance Marketplace</h3>
      ✓ Gig Management<br/>
      ✓ Order System<br/>
      ✓ Earnings Dashboard<br/>
      ✓ Profile Management
    </td>
  </tr>
</table>

---

## 📸 Screenshots

<details>
<summary><b>👉 Click to view admin panel screenshots</b></summary>

### 📊 Admin Dashboard
> Modern analytics dashboard with visitor stats, browser distribution, and device tracking

### 🤖 AI Content Generator  
> Generate blog posts, pages, and product descriptions with AI assistance

### 🛒 Product Management
> Full-featured e-commerce product management with categories and variants

### 📝 Page Builder
> Intuitive drag-and-drop page builder with live preview

### 🌐 Multilingual Manager
> Manage content in multiple languages with ease

### 🎨 Modern Frontend
> Responsive, beautiful homepage design with Tailwind CSS

</details>

---

## 🚀 Quick Start

### One-Line Install

```bash
git clone https://github.com/msrusu87-web/carpathian-cms.git && cd carpathian-cms && composer install && npm install && npm run build && php artisan migrate --seed
```

### Requirements

| Requirement | Version |
|------------|---------|
| PHP | 8.4+ |
| MySQL | 8.0+ |
| Composer | 2.x |
| Node.js | 18+ |
| Nginx/Apache | Latest |

### Basic Installation

```bash
# 1. Clone repository
git clone https://github.com/msrusu87-web/carpathian-cms.git
cd carpathian-cms

# 2. Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Setup database (update .env first)
php artisan migrate --seed

# 5. Create admin user
php artisan make:filament-user

# 6. Set permissions
chmod -R 775 storage bootstrap/cache

# 7. Start server
php artisan serve
```

Visit `http://localhost:8000/admin` to access the admin panel!

📖 **Full guide:** [Installation Documentation](docs/INSTALLATION.md)

---

## 🎯 Why Carpathian CMS?

### For Developers 👨‍💻
- ⚡ **Laravel 11** - Modern PHP framework
- 🎨 **Filament v3** - Beautiful admin panel (saves 100+ hours)
- 🧩 **Modular Architecture** - Easy to extend
- 🔒 **Secure** - Built-in security features
- 📦 **Well-documented** - Comprehensive docs

### For Content Creators ✍️
- 🤖 **AI Writing Assistant** - Generate content in seconds
- 🌐 **Multi-language** - Reach global audiences
- 📊 **Analytics** - Track visitor behavior
- 🎨 **Visual Editor** - No coding required
- 📱 **Mobile-friendly** - Works on all devices

### For Businesses 💼
- 💰 **Cost-effective** - Open source, no licensing fees
- 🚀 **Fast** - Optimized performance
- 📈 **Scalable** - Grows with your business
- 🛡️ **Secure** - Regular security updates
- 🔧 **Customizable** - Adapt to your needs

---

## 🔌 AI Integrations

Powered by cutting-edge AI:

| Provider | Model | Use Case |
|----------|-------|----------|
| **Groq** | Llama 3.1 70B | Ultra-fast content generation |
| **OpenAI** | GPT-4o | Advanced AI features |
| **Custom** | FastAPI | Self-hosted AI service |

### AI Features:
- 📝 Blog post generation
- 🔍 SEO meta descriptions  
- 🌐 Content translations
- 💬 Smart chatbot
- 🖼️ Image descriptions
- 📊 Analytics insights

**Setup guide:** [docs/AI_INTEGRATION.md](docs/AI_INTEGRATION.md)

---

## 🛒 E-Commerce Features

### Products & Catalog
✓ Unlimited products & variations  
✓ Category & tag management  
✓ Image galleries  
✓ Stock tracking  
✓ Bulk operations  
✓ Import/Export

### Orders & Payments
✓ Shopping cart  
✓ Multiple payment gateways (Stripe, PayPal)  
✓ Order management  
✓ Email notifications  
✓ Invoice generation  
✓ Tax calculations

### Customer Management
✓ User accounts  
✓ Order history  
✓ Wishlist  
✓ Reviews & ratings

---

## 🌍 Multilingual System

### Built-in Languages
- 🇬🇧 **English** - Default
- 🇷🇴 **Romanian** - Limba română  
- 🇪🇸 **Spanish** - Español

### Features:
- Easy language switcher
- SEO-friendly URLs per language
- Translation management interface
- Automated AI translations
- RTL support ready

**Add new languages in minutes!** See [docs/MULTILINGUAL.md](docs/MULTILINGUAL.md)

---

## 📚 Documentation

| Topic | Description |
|-------|-------------|
| [📥 Installation](docs/INSTALLATION.md) | Complete installation guide |
| [⚙️ Configuration](docs/CONFIGURATION.md) | System configuration |
| [🎨 Customization](docs/CUSTOMIZATION.md) | Theming and customization |
| [🤖 AI Integration](docs/AI_INTEGRATION.md) | Setup AI features |
| [🌐 Multilingual](docs/MULTILINGUAL.md) | Language management |
| [🛒 E-Commerce](docs/ECOMMERCE.md) | Shop setup and configuration |
| [🔧 Development](docs/DEVELOPMENT.md) | Development guidelines |
| [🚀 Deployment](docs/DEPLOYMENT.md) | Production deployment |
| [📖 API Reference](docs/API.md) | API documentation |

---

## 🗂️ Project Structure

```
carpathian-cms/
├── app/                    # Application code
│   ├── Filament/          # Admin panel (Resources, Pages, Widgets)
│   ├── Http/              # Controllers, middleware
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic (AI, payments, etc.)
├── database/              
│   ├── migrations/        # Database migrations
│   └── seeders/           # Sample data
├── docs/                  # Documentation files
├── lang/                  # Translations (en, ro, es)
├── public/                # Web root
├── resources/             
│   ├── views/            # Blade templates
│   └── js/css/           # Frontend assets
├── routes/                # Route definitions
└── tests/                 # Test suite
```

---

## 🛠️ Technology Stack

### Backend
- **Laravel 11.x** - PHP Framework
- **Filament v3** - Admin Panel  
- **MySQL 8.0** - Database
- **Redis** - Caching

### Frontend
- **Tailwind CSS 3** - Styling
- **Alpine.js** - JavaScript  
- **Livewire** - Dynamic components
- **Vite** - Asset bundling

### AI & Services  
- **FastAPI** - AI microservice
- **Groq API** - LLM inference
- **OpenAI API** - GPT-4o

---

## 🔐 Security Features

✅ CSRF Protection  
✅ SQL Injection Prevention  
✅ XSS Protection  
✅ SSL/TLS Encryption  
✅ Rate Limiting  
✅ Security Headers  
✅ Password Hashing  
✅ Two-Factor Auth Ready

**Report security issues:** security@carphatian.ro

---

## 🤝 Contributing

We welcome contributions! 

```bash
# Fork and clone
git clone https://github.com/YOUR_USERNAME/carpathian-cms.git

# Create branch  
git checkout -b feature/amazing-feature

# Commit changes
git commit -m 'Add amazing feature'

# Push and create PR
git push origin feature/amazing-feature
```

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

---

## 📝 License

Carpathian CMS is open-source software licensed under the [MIT License](LICENSE).

```
MIT License - Copyright (c) 2024 Carpathian CMS

Permission is hereby granted, free of charge, to any person obtaining a copy...
```

---

## 💬 Support & Community

- 📧 **Email:** support@carphatian.ro
- 🐛 **Bug Reports:** [GitHub Issues](https://github.com/msrusu87-web/carpathian-cms/issues)
- 💬 **Discussions:** [GitHub Discussions](https://github.com/msrusu87-web/carpathian-cms/discussions)
- 📚 **Documentation:** [docs/](docs/)
- 🌐 **Website:** [carphatian.ro](https://carphatian.ro)

---

## 👥 Credits & Acknowledgments

**Built with ❤️ by:**
- **Lead Developer:** [msrusu87-web](https://github.com/msrusu87-web)
- **Framework:** Laravel by Taylor Otwell
- **Admin Panel:** Filament by Dan Harrin
- **Contributors:** [All Contributors](https://github.com/msrusu87-web/carpathian-cms/graphs/contributors)

**Special Thanks:**
- Laravel Community
- Filament Community  
- All open-source contributors

---

## 🌟 Star History

If you find this project useful, please consider giving it a star! ⭐

[![Star History Chart](https://api.star-history.com/svg?repos=msrusu87-web/carpathian-cms&type=Date)](https://star-history.com/#msrusu87-web/carpathian-cms&Date)

---

## 🚀 Roadmap

### v1.1 (Coming Soon)
- [ ] Advanced SEO tools
- [ ] Email marketing integration
- [ ] More payment gateways
- [ ] Mobile app (React Native)
- [ ] Theme marketplace

### v2.0 (Future)
- [ ] Multi-vendor marketplace
- [ ] Advanced analytics
- [ ] Membership system
- [ ] Learning management system
- [ ] Forum integration

---

## 📊 Statistics

- **2000+** Lines of code
- **50+** Database tables
- **100+** Admin resources
- **3** Languages supported
- **10+** Integrations

---

## 🔗 Quick Links

| Link | URL |
|------|-----|
| 🌐 Live Demo | [carphatian.ro](https://carphatian.ro) |
| 📚 Documentation | [docs/](docs/) |
| 🐛 Issues | [GitHub Issues](https://github.com/msrusu87-web/carpathian-cms/issues) |
| 💬 Discussions | [GitHub Discussions](https://github.com/msrusu87-web/carpathian-cms/discussions) |
| 📧 Email | contact@carphatian.ro |

---

<div align="center">

**Made in Romania 🇷🇴 • Powered by Laravel ❤️ • Admin by Filament 🎨**

[⬆ Back to top](#-carpathian-cms)

---

*Star this repo if you find it useful! ⭐*

</div>

---

## 📸 Live Screenshots

> **Note:** Visit [carphatian.ro](https://carphatian.ro) to see the CMS in action!

The admin panel features:
- Modern dashboard with analytics
- AI-powered content generator
- Intuitive product management
- Drag-and-drop page builder
- Multilingual content manager
- Beautiful responsive frontend

Screenshots coming soon! For now, visit the [live demo](https://carphatian.ro) to explore all features.

