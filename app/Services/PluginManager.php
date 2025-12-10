<?php

namespace App\Services;

use App\Models\Plugin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PluginManager
{
    public function activate(Plugin $plugin): bool
    {
        try {
            $pluginClass = $this->getPluginClass($plugin->slug);
            if ($pluginClass && method_exists($pluginClass, 'activate')) {
                $pluginClass::activate();
            }

            $migrationPath = base_path("plugins/{$plugin->slug}/database/migrations");
            if (File::exists($migrationPath)) {
                // Run migrations, ignoring errors if tables already exist
                try {
                    Artisan::call('migrate', [
                        '--path' => "plugins/{$plugin->slug}/database/migrations",
                        '--force' => true
                    ]);
                } catch (\Exception $e) {
                    // Log but continue - tables might already exist
                    \Log::info("Plugin migration note: " . $e->getMessage());
                }
            }

            $this->activatePluginMenus($plugin);
            $plugin->update(['is_active' => true]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Plugin activation failed: " . $e->getMessage());
            return false;
        }
    }

    public function deactivate(Plugin $plugin): bool
    {
        try {
            $pluginClass = $this->getPluginClass($plugin->slug);
            if ($pluginClass && method_exists($pluginClass, 'deactivate')) {
                $pluginClass::deactivate();
            }
            $this->deactivatePluginMenus($plugin);
            $plugin->update(['is_active' => false]);
            return true;
        } catch (\Exception $e) {
            \Log::error("Plugin deactivation failed: " . $e->getMessage());
            return false;
        }
    }

    public function install(string $slug, array $packageData): Plugin
    {
        return Plugin::create([
            'name' => $packageData['name'],
            'slug' => $slug,
            'description' => $packageData['description'] ?? '',
            'version' => $packageData['version'] ?? '1.0.0',
            'author' => $packageData['author'] ?? 'Carphatian CMS',
            'config' => $packageData['config'] ?? [],
            'is_active' => false,
        ]);
    }

    public function uninstall(Plugin $plugin): bool
    {
        try {
            if ($plugin->is_active) {
                $this->deactivate($plugin);
            }
            $pluginClass = $this->getPluginClass($plugin->slug);
            if ($pluginClass && method_exists($pluginClass, 'uninstall')) {
                $pluginClass::uninstall();
            }
            
            // Remove plugin menus completely on uninstall
            $this->removePluginMenus($plugin);
            
            // Only rollback if we want to remove data
            // Comment this out to preserve data
            /*
            $migrationPath = base_path("plugins/{$plugin->slug}/database/migrations");
            if (File::exists($migrationPath)) {
                Artisan::call('migrate:rollback', [
                    '--path' => "plugins/{$plugin->slug}/database/migrations",
                    '--force' => true
                ]);
            }
            */
            
            $plugin->delete();
            return true;
        } catch (\Exception $e) {
            \Log::error("Plugin uninstall failed: " . $e->getMessage());
            return false;
        }
    }

    protected function activatePluginMenus(Plugin $plugin): void
    {
        $config = $plugin->config;
        if (isset($config['frontend_menus'])) {
            $this->createOrActivateFrontendMenus($plugin, $config['frontend_menus']);
        }
    }

    protected function deactivatePluginMenus(Plugin $plugin): void
    {
        $config = $plugin->config;
        if (isset($config['frontend_menus']) && class_exists(\App\Models\Menu::class)) {
            foreach ($config['frontend_menus'] as $menuData) {
                $menu = \App\Models\Menu::where('slug', $menuData['slug'])->first();
                if ($menu) {
                    // Just deactivate, don't delete
                    $menu->update(['is_active' => false]);
                }
            }
        }
    }

    protected function removePluginMenus(Plugin $plugin): void
    {
        $config = $plugin->config;
        if (isset($config['frontend_menus']) && class_exists(\App\Models\Menu::class)) {
            foreach ($config['frontend_menus'] as $menuData) {
                $menu = \App\Models\Menu::where('slug', $menuData['slug'])->first();
                if ($menu) {
                    $menu->items()->delete();
                    $menu->delete();
                }
            }
        }
    }

    protected function createOrActivateFrontendMenus(Plugin $plugin, array $menuConfig): void
    {
        if (!class_exists(\App\Models\Menu::class) || !class_exists(\App\Models\MenuItem::class)) {
            return;
        }
        
        foreach ($menuConfig as $menuData) {
            // Try to find existing menu first
            $menu = \App\Models\Menu::where('slug', $menuData['slug'])->first();
            
            if ($menu) {
                // Menu exists, just activate it and update items
                $menu->update(['is_active' => true]);
                
                // Update items if they don't exist
                if ($menu->items()->count() === 0 && isset($menuData['items'])) {
                    foreach ($menuData['items'] as $index => $item) {
                        \App\Models\MenuItem::create([
                            'menu_id' => $menu->id,
                            'title' => $item['title'],
                            'url' => $item['url'],
                            'target' => $item['target'] ?? '_self',
                            'order' => $index,
                            'parent_id' => null,
                            'icon' => $item['icon'] ?? null
                        ]);
                    }
                }
            } else {
                // Create new menu
                $menu = \App\Models\Menu::create([
                    'name' => $menuData['name'],
                    'slug' => $menuData['slug'],
                    'location' => $menuData['location'] ?? 'header',
                    'is_active' => true
                ]);
                
                // Create items
                if (isset($menuData['items'])) {
                    foreach ($menuData['items'] as $index => $item) {
                        \App\Models\MenuItem::create([
                            'menu_id' => $menu->id,
                            'title' => $item['title'],
                            'url' => $item['url'],
                            'target' => $item['target'] ?? '_self',
                            'order' => $index,
                            'parent_id' => null,
                            'icon' => $item['icon'] ?? null
                        ]);
                    }
                }
            }
        }
    }

    protected function getPluginClass(string $slug): ?string
    {
        $className = "\\Plugins\\" . Str::studly($slug) . "\\Plugin";
        return class_exists($className) ? $className : null;
    }
}
