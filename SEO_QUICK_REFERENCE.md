# 🚀 SEO Quick Reference - Carpathian CMS

## 📋 Daily Commands

```bash
# Run SEO audit and fix issues
php artisan seo:audit --fix

# Generate sitemaps
curl https://carphatian.ro/generate-sitemap
curl https://carphatian.ro/generate-news-sitemap
```

## 🔗 Important URLs

- **Main Sitemap:** https://carphatian.ro/sitemap.xml
- **News Sitemap:** https://carphatian.ro/sitemap-news.xml
- **Robots.txt:** https://carphatian.ro/robots.txt

## 💡 Using SEO Service in Views

```php
use App\Services\SeoService;

// In controller
$seo = new SeoService();
$seo->setTitle('Page Title - Carpathian CMS')
    ->setDescription('Clear, compelling description 150-160 chars')
    ->setKeywords(['keyword1', 'keyword2', 'web development'])
    ->setImage(asset('images/featured.jpg'))
    ->setCanonical(url()->current());

// Add schema
$seo->addSchema($seo->generateArticleSchema([
    'title' => $post->title,
    'description' => $post->excerpt,
    'image' => $post->featured_image,
    'author' => $post->user->name,
    'published_at' => $post->published_at->toIso8601String(),
]));

return view('blog.post', compact('seo', 'post'));
```

```blade
<!-- In view -->
<head>
    {!! $seo->render() !!}
</head>
```

## 🎨 Performance Components

```blade
<!-- In layout head -->
<x-performance-head 
    :heroImage="asset('images/hero.jpg')"
/>

<!-- For images -->
<x-lazy-image 
    src="/images/photo.jpg" 
    alt="Descriptive alt text"
    width="800"
    height="600"
/>
```

## ✅ Content Checklist

- [ ] Meta title: 50-60 characters
- [ ] Meta description: 150-160 characters
- [ ] 5-10 keywords relevant to content
- [ ] Featured image (WebP preferred)
- [ ] Alt text for all images
- [ ] Minimum 300 words content
- [ ] Internal links to related content
- [ ] Proper heading hierarchy (H1, H2, H3)
- [ ] Clean, descriptive URL slug

## 🔍 Testing Tools

- **Rich Results:** https://search.google.com/test/rich-results
- **PageSpeed:** https://pagespeed.web.dev/
- **Schema Validator:** https://validator.schema.org/
- **Mobile-Friendly:** https://search.google.com/test/mobile-friendly

## 🤖 AI Crawlers Enabled

✅ GPTBot (ChatGPT)  
✅ Claude-Web (Claude AI)  
✅ PerplexityBot (Perplexity)  
✅ Google-Extended (Bard/Gemini)  
✅ CCBot (Common Crawl)  
✅ Meta FacebookBot  
✅ All major search engines

## 📊 Schema Types Available

```php
// Organization
$seo->generateOrganizationSchema()

// Website with search
$seo->generateWebSiteSchema()

// Article/Blog Post
$seo->generateArticleSchema([...])

// Product with price
$seo->generateProductSchema([...])

// Breadcrumbs
$seo->generateBreadcrumbSchema([...])
```

## 🎯 Priority Actions

1. Submit sitemaps to Google Search Console
2. Submit sitemaps to Bing Webmaster Tools
3. Run weekly SEO audits
4. Monitor Core Web Vitals
5. Update meta tags for top pages
6. Add structured data to key pages
7. Optimize images (convert to WebP)
8. Monitor search rankings

## 📈 Monitoring

- **Google Search Console:** Track indexation
- **Google Analytics:** Monitor traffic
- **PageSpeed Insights:** Check performance
- **Rank Tracker:** Monitor keyword positions

## 🆘 Quick Fixes

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Fix permissions
chmod 644 public/robots.txt
chmod 644 public/sitemap.xml
chmod 644 public/sitemap-news.xml

# Regenerate everything
php artisan seo:audit --fix
curl https://carphatian.ro/generate-sitemap
curl https://carphatian.ro/generate-news-sitemap
```

## 📝 Content Writing Tips

**Titles:**
- Include primary keyword
- Keep under 60 characters
- Make it compelling and clickable
- Front-load important words

**Descriptions:**
- Include call-to-action
- Use active voice
- Include secondary keywords
- Be specific and accurate

**Content:**
- Write for humans first
- Use natural language
- Include relevant keywords naturally
- Use subheadings to break up text
- Add images and videos
- Link to related content

---

**Last Updated:** December 23, 2025  
**Quick Help:** See `/SEO_OPTIMIZATION_GUIDE.md` for full documentation
