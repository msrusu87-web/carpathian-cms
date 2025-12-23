<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class SeoAuditCommand extends Command
{
    protected $signature = 'seo:audit 
                          {--type=all : Type to audit (all, pages, posts, products)}
                          {--fix : Automatically fix issues where possible}
                          {--verbose : Show detailed output}';

    protected $description = 'Audit SEO issues across pages, posts, and products';

    protected $issues = [];
    protected $warnings = [];
    protected $successes = [];

    public function handle()
    {
        $type = $this->option('type');
        $fix = $this->option('fix');

        $this->info('🔍 Starting SEO Audit...');
        $this->newLine();

        if ($type === 'all' || $type === 'pages') {
            $this->auditPages($fix);
        }

        if ($type === 'all' || $type === 'posts') {
            $this->auditPosts($fix);
        }

        if ($type === 'all' || $type === 'products') {
            $this->auditProducts($fix);
        }

        $this->displayResults();

        return Command::SUCCESS;
    }

    protected function auditPages($fix = false)
    {
        if (!Schema::hasTable('pages')) {
            $this->warn('⚠️  Pages table not found');
            return;
        }

        $this->info('📄 Auditing Pages...');

        $pages = Page::where('status', 'published')->get();

        foreach ($pages as $page) {
            $this->auditContent($page, 'Page', $fix);
        }
    }

    protected function auditPosts($fix = false)
    {
        if (!Schema::hasTable('posts')) {
            $this->warn('⚠️  Posts table not found');
            return;
        }

        $this->info('📝 Auditing Posts...');

        $posts = Post::where('status', 'published')->get();

        foreach ($posts as $post) {
            $this->auditContent($post, 'Post', $fix);
        }
    }

    protected function auditProducts($fix = false)
    {
        if (!Schema::hasTable('products')) {
            $this->warn('⚠️  Products table not found');
            return;
        }

        $this->info('🛍️  Auditing Products...');

        $products = Schema::hasColumn('products', 'is_active') 
            ? Product::where('is_active', true)->get()
            : Product::all();

        foreach ($products as $product) {
            $this->auditContent($product, 'Product', $fix);
        }
    }

    protected function auditContent($item, $type, $fix = false)
    {
        $identifier = $item->title ?? $item->name ?? "ID:{$item->id}";
        
        // Check slug
        if (empty($item->slug)) {
            $this->issues[] = "❌ {$type} '{$identifier}' - Missing slug";
            if ($fix && method_exists($item, 'generateSlug')) {
                $item->generateSlug();
                $item->save();
                $this->successes[] = "✅ Fixed slug for {$type} '{$identifier}'";
            }
        } elseif (strlen($item->slug) > 100) {
            $this->warnings[] = "⚠️  {$type} '{$identifier}' - Slug too long (" . strlen($item->slug) . " chars)";
        }

        // Check meta title
        if (empty($item->meta_title)) {
            $this->issues[] = "❌ {$type} '{$identifier}' - Missing meta title";
            if ($fix) {
                $item->meta_title = $item->title ?? $item->name ?? null;
                if ($item->meta_title) {
                    $item->save();
                    $this->successes[] = "✅ Generated meta title for {$type} '{$identifier}'";
                }
            }
        } elseif (strlen($item->meta_title) > 60) {
            $this->warnings[] = "⚠️  {$type} '{$identifier}' - Meta title too long (" . strlen($item->meta_title) . " chars, recommended: 50-60)";
        } elseif (strlen($item->meta_title) < 30) {
            $this->warnings[] = "⚠️  {$type} '{$identifier}' - Meta title too short (" . strlen($item->meta_title) . " chars, recommended: 50-60)";
        }

        // Check meta description
        if (empty($item->meta_description)) {
            $this->issues[] = "❌ {$type} '{$identifier}' - Missing meta description";
            if ($fix && !empty($item->excerpt)) {
                $item->meta_description = substr(strip_tags($item->excerpt), 0, 160);
                $item->save();
                $this->successes[] = "✅ Generated meta description for {$type} '{$identifier}'";
            } elseif ($fix && !empty($item->description)) {
                $item->meta_description = substr(strip_tags($item->description), 0, 160);
                $item->save();
                $this->successes[] = "✅ Generated meta description for {$type} '{$identifier}'";
            }
        } elseif (strlen($item->meta_description) > 160) {
            $this->warnings[] = "⚠️  {$type} '{$identifier}' - Meta description too long (" . strlen($item->meta_description) . " chars, recommended: 150-160)";
        } elseif (strlen($item->meta_description) < 120) {
            $this->warnings[] = "⚠️  {$type} '{$identifier}' - Meta description too short (" . strlen($item->meta_description) . " chars, recommended: 150-160)";
        }

        // Check meta keywords
        if (empty($item->meta_keywords)) {
            $this->warnings[] = "⚠️  {$type} '{$identifier}' - Missing meta keywords";
        }

        // Check featured image
        if (isset($item->featured_image) && empty($item->featured_image)) {
            $this->warnings[] = "⚠️  {$type} '{$identifier}' - No featured image";
        }

        // Check content length for pages/posts
        if (isset($item->content)) {
            $contentLength = strlen(strip_tags($item->content));
            if ($contentLength < 300) {
                $this->warnings[] = "⚠️  {$type} '{$identifier}' - Content too short ({$contentLength} chars, recommended: >300)";
            }
        }

        // Check product-specific fields
        if ($type === 'Product') {
            if (empty($item->description)) {
                $this->issues[] = "❌ {$type} '{$identifier}' - Missing description";
            }
            if (empty($item->images) || (is_array($item->images) && count($item->images) === 0)) {
                $this->warnings[] = "⚠️  {$type} '{$identifier}' - No product images";
            }
            if (isset($item->price) && $item->price <= 0) {
                $this->issues[] = "❌ {$type} '{$identifier}' - Invalid price";
            }
        }
    }

    protected function displayResults()
    {
        $this->newLine();
        $this->info('📊 SEO Audit Results');
        $this->info('═══════════════════════════════════');
        
        if (count($this->issues) > 0) {
            $this->newLine();
            $this->error('Critical Issues (' . count($this->issues) . '):');
            foreach ($this->issues as $issue) {
                $this->line($issue);
            }
        }

        if (count($this->warnings) > 0) {
            $this->newLine();
            $this->warn('Warnings (' . count($this->warnings) . '):');
            if ($this->option('verbose')) {
                foreach ($this->warnings as $warning) {
                    $this->line($warning);
                }
            } else {
                $this->line('Run with --verbose to see all warnings');
            }
        }

        if (count($this->successes) > 0) {
            $this->newLine();
            $this->info('Fixed Issues (' . count($this->successes) . '):');
            foreach ($this->successes as $success) {
                $this->line($success);
            }
        }

        $this->newLine();
        $totalIssues = count($this->issues) + count($this->warnings);
        
        if ($totalIssues === 0) {
            $this->info('✨ Great! No SEO issues found!');
        } else {
            $this->warn("Found {$totalIssues} issues total");
            if (!$this->option('fix')) {
                $this->info('Run with --fix to automatically fix issues');
            }
        }
        
        $this->newLine();
    }
}
