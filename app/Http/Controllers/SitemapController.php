<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();
        
        // Add homepage
        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );
        
        // Add static important pages
        $sitemap->add(
            Url::create('/portfolios')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );
        
        $sitemap->add(
            Url::create('/blog')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );
        
        $sitemap->add(
            Url::create('/contact')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.8)
        );
        
        // Add published pages
        if (Schema::hasTable('pages')) {
            Page::where('status', 'published')
                ->whereNotNull('published_at')
                ->get()
                ->each(function (Page $page) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/page/{$page->slug}")
                            ->setLastModificationDate($page->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.8)
                    );
                });
        }
        
        // Add published posts/blogs
        if (Schema::hasTable('posts')) {
            Post::where('status', 'published')
                ->whereNotNull('published_at')
                ->get()
                ->each(function (Post $post) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/blog/{$post->slug}")
                            ->setLastModificationDate($post->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.7)
                    );
                });
        }
        
        // Add categories if they have posts
        if (Schema::hasTable('categories')) {
            Category::has('posts')
                ->get()
                ->each(function (Category $category) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/category/{$category->slug}")
                            ->setLastModificationDate($category->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.6)
                    );
                });
        }
        
        // Add products if table exists and has status column
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'status')) {
            Product::where('status', 'published')
                ->get()
                ->each(function (Product $product) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/product/{$product->slug}")
                            ->setLastModificationDate($product->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7)
                    );
                });
        } elseif (Schema::hasTable('products')) {
            // If products table exists but no status column, add all products
            Product::all()->each(function (Product $product) use ($sitemap) {
                $sitemap->add(
                    Url::create("/product/{$product->slug}")
                        ->setLastModificationDate($product->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            });
        }
        
        return response($sitemap->render(), 200)
            ->header('Content-Type', 'text/xml');
    }
    
    public function generate()
    {
        $sitemap = Sitemap::create();
        
        // Same logic as index but save to file
        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );
        
        // Add static important pages
        $sitemap->add(
            Url::create('/portfolios')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );
        
        $sitemap->add(
            Url::create('/blog')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );
        
        $sitemap->add(
            Url::create('/contact')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.8)
        );
        
        if (Schema::hasTable('pages')) {
            Page::where('status', 'published')
                ->whereNotNull('published_at')
                ->get()
                ->each(function (Page $page) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/page/{$page->slug}")
                            ->setLastModificationDate($page->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.8)
                    );
                });
        }
        
        if (Schema::hasTable('posts')) {
            Post::where('status', 'published')
                ->whereNotNull('published_at')
                ->get()
                ->each(function (Post $post) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/blog/{$post->slug}")
                            ->setLastModificationDate($post->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.7)
                    );
                });
        }
        
        if (Schema::hasTable('categories')) {
            Category::has('posts')
                ->get()
                ->each(function (Category $category) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/category/{$category->slug}")
                            ->setLastModificationDate($category->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.6)
                    );
                });
        }
        
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'status')) {
            Product::where('status', 'published')
                ->get()
                ->each(function (Product $product) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/product/{$product->slug}")
                            ->setLastModificationDate($product->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7)
                    );
                });
        } elseif (Schema::hasTable('products')) {
            Product::all()->each(function (Product $product) use ($sitemap) {
                $sitemap->add(
                    Url::create("/product/{$product->slug}")
                        ->setLastModificationDate($product->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            });
        }
        
        $sitemap->writeToFile(public_path('sitemap.xml'));
        
        return response()->json([
            'success' => true,
            'message' => 'Sitemap generated successfully',
            'path' => public_path('sitemap.xml'),
        ]);
    }
}
