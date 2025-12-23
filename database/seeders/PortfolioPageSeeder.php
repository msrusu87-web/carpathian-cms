<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Menu;
use App\Models\MenuItem;

class PortfolioPageSeeder extends Seeder
{
    public function run(): void
    {
                $content = <<<'HTML'
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Portfolios</h1>
        <p class="text-lg text-gray-700">A curated selection of products and experiments we’ve built—each with a live demo and a real preview.</p>
    </div>

    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <article class="group rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <a href="https://chat.carphatian.ro" target="_blank" rel="noopener noreferrer" class="block">
                <div class="aspect-video bg-gray-50 overflow-hidden">
                    <img src="/images/portfolio/saas-marketplace.jpg" alt="Carpathian AI SaaS Marketplace preview" class="h-full w-full object-cover" loading="lazy" />
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700">Carpathian AI SaaS Marketplace</h3>
                    <p class="mt-2 text-sm text-gray-600">A marketplace for AI tools and assistants with a clean UX and fast navigation.</p>
                    <div class="mt-4 text-sm font-medium text-blue-700">Visit →</div>
                </div>
            </a>
        </article>

        <article class="group rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <a href="https://social.carphatian.ro" target="_blank" rel="noopener noreferrer" class="block">
                <div class="aspect-video bg-gray-50 overflow-hidden">
                    <img src="/images/portfolio/demo-tools.jpg" alt="Demo tools preview" class="h-full w-full object-cover" loading="lazy" />
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700">Demo Tools Portfolio</h3>
                    <p class="mt-2 text-sm text-gray-600">A sandbox of small, practical tools to demonstrate features and integrations.</p>
                    <div class="mt-4 text-sm font-medium text-blue-700">Visit →</div>
                </div>
            </a>
        </article>

        <article class="group rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <a href="https://social.carphatian.ro" target="_blank" rel="noopener noreferrer" class="block">
                <div class="aspect-video bg-gray-50 overflow-hidden">
                    <img src="/images/portfolio/pdf-generator.jpg" alt="PDF summary generator preview" class="h-full w-full object-cover" loading="lazy" />
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700">PDF Summary Generator</h3>
                    <p class="mt-2 text-sm text-gray-600">Upload documents and generate clean summaries and exports for quick reading.</p>
                    <div class="mt-4 text-sm font-medium text-blue-700">Visit →</div>
                </div>
            </a>
        </article>

        <article class="group rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <a href="https://explorer.carphatian.ro" target="_blank" rel="noopener noreferrer" class="block">
                <div class="aspect-video bg-gray-50 overflow-hidden">
                    <img src="/images/portfolio/antimony-coin.jpg" alt="ATMN coin explorer preview" class="h-full w-full object-cover" loading="lazy" />
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700">ATMN Coin Explorer</h3>
                    <p class="mt-2 text-sm text-gray-600">Explore transactions and blocks with a fast, searchable interface.</p>
                    <div class="mt-4 text-sm font-medium text-blue-700">Visit →</div>
                </div>
            </a>
        </article>

        <article class="group rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <a href="https://antimony.carphatian.ro" target="_blank" rel="noopener noreferrer" class="block">
                <div class="aspect-video bg-gray-50 overflow-hidden">
                    <img src="/images/portfolio/language-detection.jpg" alt="Language detection preview" class="h-full w-full object-cover" loading="lazy" />
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700">Language Detection</h3>
                    <p class="mt-2 text-sm text-gray-600">Paste text and detect language quickly—useful for routing content and translation flows.</p>
                    <div class="mt-4 text-sm font-medium text-blue-700">Visit →</div>
                </div>
            </a>
        </article>

        <article class="group rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <a href="https://cms.carphatian.ro" target="_blank" rel="noopener noreferrer" class="block">
                <div class="aspect-video bg-gray-50 overflow-hidden">
                    <img src="/images/portfolio/carpathian-cms.jpg" alt="Carpathian CMS preview" class="h-full w-full object-cover" loading="lazy" />
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700">Carpathian CMS</h3>
                    <p class="mt-2 text-sm text-gray-600">A CMS/admin experience built for speed: manage pages, content, and site structure.</p>
                    <div class="mt-4 text-sm font-medium text-blue-700">Visit →</div>
                </div>
            </a>
        </article>
    </div>
</div>
HTML;

        // Create or update the Portfolio page
        $portfolioPage = Page::updateOrCreate(
            ['slug' => 'portfolios'],
            [
                'title' => 'Portfolios',
                                'content' => $content,
                'status' => 'published',
                'user_id' => 1,
                'show_in_menu' => true,
                'meta_title' => 'Our Portfolios - Carpathian',
                'meta_description' => 'View our portfolio of projects and work',
                'published_at' => now(),
            ]
        );

        // Get the top menu (header location)
        $topMenu = Menu::where('location', 'top')->first();

        if ($topMenu) {
            // Get the current highest order value for menu items
            $maxOrder = MenuItem::where('menu_id', $topMenu->id)->max('order') ?? 0;

            // Create the Portfolios menu item
            $blogMenuItem = MenuItem::where('menu_id', $topMenu->id)
                ->where('title', 'Blog')
                ->first();

            if ($blogMenuItem) {
                // Insert before Blog
                $orderValue = $blogMenuItem->order;
                
                // Update all items with order >= Blog's order to make space
                MenuItem::where('menu_id', $topMenu->id)
                    ->where('order', '>=', $orderValue)
                    ->increment('order');
            } else {
                // Add at the end
                $orderValue = $maxOrder + 1;
            }

            MenuItem::updateOrCreate(
                ['menu_id' => $topMenu->id, 'title' => 'Portfolios'],
                [
                    'url' => '/portfolios',
                    'type' => 'page',
                    'reference_id' => $portfolioPage->id,
                    'order' => $orderValue,
                    'is_active' => true,
                    'target' => '_self'
                ]
            );
        }
    }
}
