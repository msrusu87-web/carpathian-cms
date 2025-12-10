<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Widget;

class WidgetSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing widgets
        Widget::truncate();

        // Hero Widget
        Widget::create([
            'title' => 'Hero Section',
            'type' => 'hero',
            'content' => '<h1>Welcome to Web Agency CMS</h1><p>Professional CMS with AI integration</p>',
            'settings' => [
                'button1_text' => 'Explore Shop',
                'button1_url' => '/shop',
                'button2_text' => 'Try AI Writer',
                'button2_url' => '/admin/ai-content-writer',
                'background_gradient' => 'from-blue-500 to-purple-600',
            ],
            'order' => 1,
            'status' => 'active',
        ]);

        // Features Widget
        Widget::create([
            'title' => 'Powerful Features',
            'type' => 'features',
            'content' => '<h2>Powerful Features</h2>',
            'settings' => [
                'features' => [
                    [
                        'icon' => '🤖',
                        'title' => 'AI-Powered',
                        'description' => 'Generate content with AI assistance',
                    ],
                    [
                        'icon' => '🛒',
                        'title' => 'E-Commerce Ready',
                        'description' => 'Full shop with products, cart & checkout',
                    ],
                    [
                        'icon' => '⚙️',
                        'title' => 'Easy Management',
                        'description' => 'Manage everything from admin panel',
                    ],
                ],
            ],
            'order' => 2,
            'status' => 'active',
        ]);

        // Products Section Widget
        Widget::create([
            'title' => 'Featured Products',
            'type' => 'products',
            'content' => '<h2>Featured Products</h2><p>Check out our latest offerings</p>',
            'settings' => [
                'show_count' => 6,
                'show_add_to_cart' => true,
            ],
            'order' => 3,
            'status' => 'active',
        ]);

        // Blog Section Widget
        Widget::create([
            'title' => 'Latest Blog Posts',
            'type' => 'blog',
            'content' => '<h2>Latest from Our Blog</h2><p>Stay updated with our latest articles</p>',
            'settings' => [
                'show_count' => 3,
            ],
            'order' => 4,
            'status' => 'active',
        ]);

        // Footer Widget
        Widget::create([
            'title' => 'Footer',
            'type' => 'footer',
            'content' => '<h3>ModularCMS</h3><p>Your partner in digital success.</p>',
            'settings' => [
                'show_pages' => true,
                'show_more' => true,
                'columns' => 4,
            ],
            'order' => 5,
            'status' => 'active',
        ]);

        // Copyright Widget
        Widget::create([
            'title' => 'Copyright',
            'type' => 'copyright',
            'content' => '© 2025 ModularCMS. All rights reserved.',
            'settings' => [],
            'order' => 6,
            'status' => 'active',
        ]);
    }
}
