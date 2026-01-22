<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SeoService
{
    protected $title;
    protected $description;
    protected $keywords = [];
    protected $image;
    protected $url;
    protected $type = 'website';
    protected $locale;
    protected $alternateLanguages = ['ro', 'en', 'de', 'es', 'fr', 'it'];
    protected $canonicalUrl;
    protected $robots = 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
    protected $schema = [];

    public function __construct()
    {
        $this->locale = App::getLocale();
        $this->url = URL::current();
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setKeywords(array $keywords): self
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setCanonical(string $url): self
    {
        $this->canonicalUrl = $url;
        return $this;
    }

    public function setRobots(string $robots): self
    {
        $this->robots = $robots;
        return $this;
    }

    public function addSchema(array $schema): self
    {
        $this->schema[] = $schema;
        return $this;
    }

    public function generateMetaTags(): string
    {
        $tags = [];

        // Basic meta tags
        $tags[] = '<meta charset="UTF-8">';
        $tags[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        
        // Title
        if ($this->title) {
            $tags[] = "<title>{$this->title}</title>";
            $tags[] = '<meta name="title" content="' . htmlspecialchars($this->title) . '">';
        }

        // Description
        if ($this->description) {
            $tags[] = '<meta name="description" content="' . htmlspecialchars($this->description) . '">';
        }

        // Keywords
        if (!empty($this->keywords)) {
            $tags[] = '<meta name="keywords" content="' . htmlspecialchars(implode(', ', $this->keywords)) . '">';
        }

        // Robots
        $tags[] = '<meta name="robots" content="' . htmlspecialchars($this->robots) . '">';

        // Canonical URL
        if ($this->canonicalUrl) {
            $tags[] = '<link rel="canonical" href="' . htmlspecialchars($this->canonicalUrl) . '">';
        }

        // Language
        $tags[] = '<meta name="language" content="' . htmlspecialchars($this->locale) . '">';
        $tags[] = '<meta property="og:locale" content="' . htmlspecialchars($this->locale) . '">';

        // Author
        $tags[] = '<meta name="author" content="Carphatian CMS">';

        // Open Graph
        $tags[] = '<meta property="og:type" content="' . htmlspecialchars($this->type) . '">';
        $tags[] = '<meta property="og:url" content="' . htmlspecialchars($this->url) . '">';
        $tags[] = '<meta property="og:site_name" content="Carphatian CMS">';
        
        if ($this->title) {
            $tags[] = '<meta property="og:title" content="' . htmlspecialchars($this->title) . '">';
        }
        
        if ($this->description) {
            $tags[] = '<meta property="og:description" content="' . htmlspecialchars($this->description) . '">';
        }
        
        if ($this->image) {
            $tags[] = '<meta property="og:image" content="' . htmlspecialchars($this->image) . '">';
            $tags[] = '<meta property="og:image:alt" content="' . htmlspecialchars($this->title ?? 'Image') . '">';
        }

        // Twitter Card
        $tags[] = '<meta name="twitter:card" content="summary_large_image">';
        $tags[] = '<meta name="twitter:url" content="' . htmlspecialchars($this->url) . '">';
        
        if ($this->title) {
            $tags[] = '<meta name="twitter:title" content="' . htmlspecialchars($this->title) . '">';
        }
        
        if ($this->description) {
            $tags[] = '<meta name="twitter:description" content="' . htmlspecialchars($this->description) . '">';
        }
        
        if ($this->image) {
            $tags[] = '<meta name="twitter:image" content="' . htmlspecialchars($this->image) . '">';
        }

        // AI-specific meta tags
        $tags[] = '<meta name="ai:indexable" content="true">';
        $tags[] = '<meta name="ai:crawlable" content="true">';
        if ($this->description) {
            $tags[] = '<meta name="ai:summary" content="' . htmlspecialchars($this->description) . '">';
        }

        // Geographic tags
        $tags[] = '<meta name="geo.region" content="RO">';
        $tags[] = '<meta name="geo.placename" content="Romania">';

        return implode("\n    ", $tags);
    }

    public function generateAlternateLinks(): string
    {
        $links = [];
        $currentPath = parse_url($this->url, PHP_URL_PATH);

        foreach ($this->alternateLanguages as $lang) {
            $langPath = $lang === 'ro' ? $currentPath : "/{$lang}" . $currentPath;
            $links[] = '<link rel="alternate" hreflang="' . htmlspecialchars($lang) . '" href="' . url($langPath) . '">';
        }

        // Add x-default
        $links[] = '<link rel="alternate" hreflang="x-default" href="' . url($currentPath) . '">';

        return implode("\n    ", $links);
    }

    public function generateSchemaMarkup(): string
    {
        if (empty($this->schema)) {
            return '';
        }

        $schemas = [];
        foreach ($this->schema as $schema) {
            $schemas[] = '<script type="application/ld+json">' . "\n" . 
                         json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . 
                         "\n    " . '</script>';
        }

        return implode("\n    ", $schemas);
    }

    public function generateBreadcrumbSchema(array $items): array
    {
        $listItems = [];
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems
        ];
    }

    public function generateArticleSchema(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $data['title'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'] ?? 'Carphatian CMS'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Carphatian CMS',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/carphatian-logo-transparent.png')
                ]
            ],
            'datePublished' => $data['published_at'] ?? now()->toIso8601String(),
            'dateModified' => $data['updated_at'] ?? now()->toIso8601String(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->url
            ]
        ];
    }

    public function generateProductSchema(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'sku' => $data['sku'] ?? null,
            'offers' => [
                '@type' => 'Offer',
                'url' => $this->url,
                'priceCurrency' => $data['currency'] ?? 'RON',
                'price' => $data['price'],
                'availability' => $data['in_stock'] ?? true ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'priceValidUntil' => now()->addYear()->format('Y-m-d')
            ]
        ];

        if (isset($data['rating'])) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $data['rating'],
                'reviewCount' => $data['review_count'] ?? 1
            ];
        }

        return $schema;
    }

    public function generateOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Carphatian CMS',
            'url' => url('/'),
            'logo' => asset('images/carphatian-logo-transparent.png'),
            'description' => 'Professional CMS platform for web development on demand',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'RO'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service',
                'availableLanguage' => ['Romanian', 'English', 'Spanish', 'Italian', 'German', 'French'],
                'email' => 'contact@carphatian.ro'
            ],
            'sameAs' => [
                // Add social media URLs here
            ]
        ];
    }

    public function generateWebSiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Carphatian CMS',
            'url' => url('/'),
            'description' => 'Professional CMS platform with multilingual support',
            'inLanguage' => $this->alternateLanguages,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/search') . '?q={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    public function render(): string
    {
        return $this->generateMetaTags() . "\n    " . 
               $this->generateAlternateLinks() . "\n    " . 
               $this->generateSchemaMarkup();
    }
}
