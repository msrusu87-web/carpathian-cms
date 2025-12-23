<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    protected $languages = ['ro', 'en', 'de', 'es', 'fr', 'it'];
    
    public function index()
    {
        $sitemap = Sitemap::create();
        
        // Add homepage for all languages
        foreach ($this->languages as $lang) {
            $sitemap->add(
                Url::create($lang === 'ro' ? '/' : "/{$lang}")
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
                    ->addAlternate(url('/'), 'ro')
                    ->addAlternate(url('/en'), 'en')
                    ->addAlternate(url('/de'), 'de')
                    ->addAlternate(url('/es'), 'es')
                    ->addAlternate(url('/fr'), 'fr')
                    ->addAlternate(url('/it'), 'it')
            );
        }
        
        // Add shop page for all languages
        foreach ($this->languages as $lang) {
            $sitemap->add(
                Url::create($lang === 'ro' ? '/shop' : "/{$lang}/shop")
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9)
            );
        }
        
        // Add static important pages for all languages
        foreach ($this->languages as $lang) {
            $sitemap->add(
                Url::create($lang === 'ro' ? '/portfolio' : "/{$lang}/portfolio")
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9)
            );
        }
        
        foreach ($this->languages as $lang) {
            $sitemap->add(
                Url::create($lang === 'ro' ? '/blog' : "/{$lang}/blog")
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.9)
            );
        }
        
        foreach ($this->languages as $lang) {
            $sitemap->add(
                Url::create($lang === 'ro' ? '/contact' : "/{$lang}/contact")
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.8)
            );
        }
        
        // Add published pages (multilingual with hreflang)
        if (Schema::hasTable('pages')) {
            Page::where('status', 'published')
                ->whereNotNull('published_at')
                ->get()
                ->each(function (Page $page) use ($sitemap) {
                    foreach ($this->languages as $lang) {
                        $url = Url::create($lang === 'ro' ? "/{$page->slug}" : "/{$lang}/{$page->slug}")
                            ->setLastModificationDate($page->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.8);
                        
                        // Add hreflang alternates for all languages
                        foreach ($this->languages as $altLang) {
                            $url->addAlternate(
                                url($altLang === 'ro' ? "/{$page->slug}" : "/{$altLang}/{$page->slug}"),
                                $altLang
                            );
                        }
                        
                        $sitemap->add($url);
                    }
                });
        }
        
        // Add published posts/blogs (multilingual with hreflang)
        if (Schema::hasTable('posts')) {
            Post::where('status', 'published')
                ->whereNotNull('published_at')
                ->get()
                ->each(function (Post $post) use ($sitemap) {
                    foreach ($this->languages as $lang) {
                        $url = Url::create($lang === 'ro' ? "/blog/{$post->slug}" : "/{$lang}/blog/{$post->slug}")
                            ->setLastModificationDate($post->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.7);
                        
                        // Add hreflang alternates
                        foreach ($this->languages as $altLang) {
                            $url->addAlternate(
                                url($altLang === 'ro' ? "/blog/{$post->slug}" : "/{$altLang}/blog/{$post->slug}"),
                                $altLang
                            );
                        }
                        
                        $sitemap->add($url);
                    }
                });
        }
        
        // Add categories if they have posts (multilingual)
        if (Schema::hasTable('categories')) {
            Category::has('posts')
                ->get()
                ->each(function (Category $category) use ($sitemap) {
                    foreach ($this->languages as $lang) {
                        $sitemap->add(
                            Url::create($lang === 'ro' ? "/category/{$category->slug}" : "/{$lang}/category/{$category->slug}")
                                ->setLastModificationDate($category->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setPriority(0.6)
                        );
                    }
                });
        }
        
        // Add products (multilingual with hreflang)
        if (Schema::hasTable('products')) {
            $productsQuery = Schema::hasColumn('products', 'is_active') 
                ? Product::where('is_active', true)
                : Product::query();
                
            $productsQuery->get()->each(function (Product $product) use ($sitemap) {
                foreach ($this->languages as $lang) {
                    $url = Url::create($lang === 'ro' ? "/shop/products/{$product->slug}" : "/{$lang}/shop/products/{$product->slug}")
                        ->setLastModificationDate($product->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7);
                    
                    // Add hreflang alternates
                    foreach ($this->languages as $altLang) {
                        $url->addAlternate(
                            url($altLang === 'ro' ? "/shop/products/{$product->slug}" : "/{$altLang}/shop/products/{$product->slug}"),
                            $altLang
                        );
                    }
                    
                    $sitemap->add($url);
                }
            });
        }
        
        // Add portfolio items if table exists
        if (Schema::hasTable('portfolios')) {
            \App\Models\Portfolio::query()->get()->each(function ($portfolio) use ($sitemap) {
                foreach ($this->languages as $lang) {
                    $sitemap->add(
                        Url::create($lang === 'ro' ? "/portfolio/{$portfolio->slug}" : "/{$lang}/portfolio/{$portfolio->slug}")
                            ->setLastModificationDate($portfolio->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.6)
                    );
                }
            });
        }
        
        return response($sitemap->render(), 200)
            ->header('Content-Type', 'text/xml');
    }
    
    public function generate()
    {
        $sitemap = Sitemap::create();
        
        // Add homepage for all languages
        foreach ($this->languages as $lang) {
            $sitemap->add(
                Url::create($lang === 'ro' ? '/' : "/{$lang}")
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );
        }
        
        // Add shop, portfolio, blog, contact pages for all languages
        foreach (['shop', 'portfolio', 'blog', 'contact'] as $page) {
            foreach ($this->languages as $lang) {
                $priority = $page === 'shop' || $page === 'blog' ? 0.9 : 0.8;
                $sitemap->add(
                    Url::create($lang === 'ro' ? "/{$page}" : "/{$lang}/{$page}")
                        ->setLastModificationDate(now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority($priority)
                );
            }
        }
        
        // Add published pages (all languages)
        if (Schema::hasTable('pages')) {
            Page::where('status', 'published')
                ->whereNotNull('published_at')
                ->get()
                ->each(function (Page $page) use ($sitemap) {
                    foreach ($this->languages as $lang) {
                        $sitemap->add(
                            Url::create($lang === 'ro' ? "/{$page->slug}" : "/{$lang}/{$page->slug}")
                                ->setLastModificationDate($page->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setPriority(0.8)
                        );
                    }
                });
        }
        
        // Add published posts (all languages)
        if (Schema::hasTable('posts')) {
            Post::where('status', 'published')
                ->whereNotNull('published_at')
                ->get()
                ->each(function (Post $post) use ($sitemap) {
                    foreach ($this->languages as $lang) {
                        $sitemap->add(
                            Url::create($lang === 'ro' ? "/blog/{$post->slug}" : "/{$lang}/blog/{$post->slug}")
                                ->setLastModificationDate($post->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                                ->setPriority(0.7)
                        );
                    }
                });
        }
        
        // Add categories (all languages)
        if (Schema::hasTable('categories')) {
            Category::has('posts')
                ->get()
                ->each(function (Category $category) use ($sitemap) {
                    foreach ($this->languages as $lang) {
                        $sitemap->add(
                            Url::create($lang === 'ro' ? "/category/{$category->slug}" : "/{$lang}/category/{$category->slug}")
                                ->setLastModificationDate($category->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setPriority(0.6)
                        );
                    }
                });
        }
        
        // Add products (all languages)
        if (Schema::hasTable('products')) {
            $productsQuery = Schema::hasColumn('products', 'is_active') 
                ? Product::where('is_active', true)
                : Product::query();
                
            $productsQuery->get()->each(function (Product $product) use ($sitemap) {
                foreach ($this->languages as $lang) {
                    $sitemap->add(
                        Url::create($lang === 'ro' ? "/shop/products/{$product->slug}" : "/{$lang}/shop/products/{$product->slug}")
                            ->setLastModificationDate($product->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7)
                    );
                }
            });
        }
        
        // Add portfolio items
        if (Schema::hasTable('portfolios')) {
            \App\Models\Portfolio::query()->get()->each(function ($portfolio) use ($sitemap) {
                foreach ($this->languages as $lang) {
                    $sitemap->add(
                        Url::create($lang === 'ro' ? "/portfolio/{$portfolio->slug}" : "/{$lang}/portfolio/{$portfolio->slug}")
                            ->setLastModificationDate($portfolio->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.6)
                    );
                }
            });
        }
        
        $sitemap->writeToFile(public_path('sitemap.xml'));
        
        // Add XSL stylesheet to the generated XML
        $xmlContent = file_get_contents(public_path('sitemap.xml'));
        $xmlContent = str_replace(
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>',
            $xmlContent
        );
        file_put_contents(public_path('sitemap.xml'), $xmlContent);
        
        return response()->json([
            'success' => true,
            'message' => 'Multilingual sitemap generated successfully',
            'path' => public_path('sitemap.xml'),
            'languages' => $this->languages,
        ]);
    }
}
