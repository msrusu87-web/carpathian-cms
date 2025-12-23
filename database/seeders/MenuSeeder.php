<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Create Top Menu
        $topMenu = Menu::updateOrCreate(
            ['location' => 'top'],
            [
                'name' => 'Top Navigation',
                'description' => 'Main navigation menu at the top of the site',
                'is_active' => true,
                'order' => 1
            ]
        );

        // Create Footer Menu
        $footerMenu = Menu::updateOrCreate(
            ['location' => 'footer'],
            [
                'name' => 'Footer Links',
                'description' => 'Links displayed in the footer',
                'is_active' => true,
                'order' => 2
            ]
        );

        // Create Sidebar Left Menu
        $leftMenu = Menu::updateOrCreate(
            ['location' => 'sidebar_left'],
            [
                'name' => 'Left Sidebar',
                'description' => 'Sidebar menu on the left',
                'is_active' => false,
                'order' => 3
            ]
        );

        // Create Sidebar Right Menu
        $rightMenu = Menu::updateOrCreate(
            ['location' => 'sidebar_right'],
            [
                'name' => 'Right Sidebar',
                'description' => 'Sidebar menu on the right',
                'is_active' => false,
                'order' => 4
            ]
        );

        // Add items to Top Menu
        $pages = Page::where('status', 'published')->get();
        
        // Home
        MenuItem::updateOrCreate(
            ['menu_id' => $topMenu->id, 'title' => 'Home'],
            [
                'url' => '/',
                'type' => 'custom',
                'order' => 1,
                'is_active' => true
            ]
        );

        $order = 2;
        foreach ($pages as $page) {
            if (!$page->is_homepage && $page->slug !== 'contact') {
                MenuItem::updateOrCreate(
                    ['menu_id' => $topMenu->id, 'reference_id' => $page->id, 'type' => 'page'],
                    [
                        'title' => $page->title,
                        'type' => 'page',
                        'reference_id' => $page->id,
                        'order' => $order++,
                        'is_active' => true
                    ]
                );
            }
        }

        // Blog
        MenuItem::updateOrCreate(
            ['menu_id' => $topMenu->id, 'title' => 'Blog'],
            [
                'url' => '/blog',
                'type' => 'custom',
                'order' => $order++,
                'is_active' => true
            ]
        );

        // Shop
        MenuItem::updateOrCreate(
            ['menu_id' => $topMenu->id, 'title' => 'Shop'],
            [
                'url' => '/shop',
                'type' => 'custom',
                'order' => $order++,
                'is_active' => true
            ]
        );

        // Contact
        MenuItem::updateOrCreate(
            ['menu_id' => $topMenu->id, 'title' => 'Contact'],
            [
                'url' => '/contact',
                'type' => 'custom',
                'order' => $order++,
                'is_active' => true
            ]
        );
    }
}
