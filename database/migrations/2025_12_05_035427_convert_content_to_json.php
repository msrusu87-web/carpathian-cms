<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert products
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            DB::table('products')->where('id', $product->id)->update([
                'name' => json_encode(['en' => $product->name, 'ro' => $product->name]),
                'description' => json_encode(['en' => $product->description, 'ro' => $product->description]),
                'content' => json_encode(['en' => $product->content, 'ro' => $product->content]),
            ]);
        }

        // Convert pages
        $pages = DB::table('pages')->get();
        foreach ($pages as $page) {
            DB::table('pages')->where('id', $page->id)->update([
                'title' => json_encode(['en' => $page->title, 'ro' => $page->title]),
                'content' => json_encode(['en' => $page->content, 'ro' => $page->content]),
                'excerpt' => json_encode(['en' => $page->excerpt, 'ro' => $page->excerpt]),
            ]);
        }

        // Convert posts
        $posts = DB::table('posts')->get();
        foreach ($posts as $post) {
            DB::table('posts')->where('id', $post->id)->update([
                'title' => json_encode(['en' => $post->title, 'ro' => $post->title]),
                'content' => json_encode(['en' => $post->content, 'ro' => $post->content]),
                'excerpt' => json_encode(['en' => $post->excerpt, 'ro' => $post->excerpt]),
            ]);
        }

        // Convert categories
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            DB::table('categories')->where('id', $category->id)->update([
                'name' => json_encode(['en' => $category->name, 'ro' => $category->name]),
                'description' => json_encode(['en' => $category->description, 'ro' => $category->description]),
            ]);
        }

        // Convert product categories
        $productCategories = DB::table('product_categories')->get();
        foreach ($productCategories as $category) {
            DB::table('product_categories')->where('id', $category->id)->update([
                'name' => json_encode(['en' => $category->name, 'ro' => $category->name]),
                'description' => json_encode(['en' => $category->description, 'ro' => $category->description]),
            ]);
        }
    }

    public function down(): void
    {
        // Convert back to strings by taking English version
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $name = json_decode($product->name, true);
            $description = json_decode($product->description, true);
            $content = json_decode($product->content, true);
            
            DB::table('products')->where('id', $product->id)->update([
                'name' => is_array($name) ? ($name['en'] ?? '') : $name,
                'description' => is_array($description) ? ($description['en'] ?? '') : $description,
                'content' => is_array($content) ? ($content['en'] ?? '') : $content,
            ]);
        }

        $pages = DB::table('pages')->get();
        foreach ($pages as $page) {
            $title = json_decode($page->title, true);
            $content = json_decode($page->content, true);
            $excerpt = json_decode($page->excerpt, true);
            
            DB::table('pages')->where('id', $page->id)->update([
                'title' => is_array($title) ? ($title['en'] ?? '') : $title,
                'content' => is_array($content) ? ($content['en'] ?? '') : $content,
                'excerpt' => is_array($excerpt) ? ($excerpt['en'] ?? '') : $excerpt,
            ]);
        }

        $posts = DB::table('posts')->get();
        foreach ($posts as $post) {
            $title = json_decode($post->title, true);
            $content = json_decode($post->content, true);
            $excerpt = json_decode($post->excerpt, true);
            
            DB::table('posts')->where('id', $post->id)->update([
                'title' => is_array($title) ? ($title['en'] ?? '') : $title,
                'content' => is_array($content) ? ($content['en'] ?? '') : $content,
                'excerpt' => is_array($excerpt) ? ($excerpt['en'] ?? '') : $excerpt,
            ]);
        }

        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            $name = json_decode($category->name, true);
            $description = json_decode($category->description, true);
            
            DB::table('categories')->where('id', $category->id)->update([
                'name' => is_array($name) ? ($name['en'] ?? '') : $name,
                'description' => is_array($description) ? ($description['en'] ?? '') : $description,
            ]);
        }

        $productCategories = DB::table('product_categories')->get();
        foreach ($productCategories as $category) {
            $name = json_decode($category->name, true);
            $description = json_decode($category->description, true);
            
            DB::table('product_categories')->where('id', $category->id)->update([
                'name' => is_array($name) ? ($name['en'] ?? '') : $name,
                'description' => is_array($description) ? ($description['en'] ?? '') : $description,
            ]);
        }
    }
};
