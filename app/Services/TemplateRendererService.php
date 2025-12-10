<?php

namespace App\Services;

use App\Models\Template;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;

class TemplateRendererService
{
    protected ?Template $activeTemplate = null;

    public function __construct()
    {
        $this->activeTemplate = Template::active()->first() ?? Template::default()->first();
    }

    public function renderPage(Page $page): string
    {
        $template = $page->template ?? $this->activeTemplate;
        
        return $this->render($template, [
            'type' => 'page',
            'content' => $page,
            'title' => $page->title,
            'meta_title' => $page->meta_title ?? $page->title,
            'meta_description' => $page->meta_description,
            'content_html' => $page->content
        ]);
    }

    public function renderPost(Post $post): string
    {
        $template = $post->template ?? $this->activeTemplate;
        
        return $this->render($template, [
            'type' => 'post',
            'content' => $post,
            'title' => $post->title,
            'meta_title' => $post->meta_title ?? $post->title,
            'meta_description' => $post->meta_description ?? $post->excerpt,
            'content_html' => $post->content,
            'author' => $post->author,
            'category' => $post->category,
            'tags' => $post->tags
        ]);
    }

    protected function render(?Template $template, array $variables): string
    {
        if (!$template) {
            return $this->renderFallback($variables);
        }

        $cacheKey = "template_render_{$template->id}_" . md5(json_encode($variables));
        
        // Clear cache to force fresh render
        Cache::forget($cacheKey);
        
        return Cache::remember($cacheKey, 3600, function () use ($template, $variables) {
            // Handle layouts - decode JSON if it's a string
            $layouts = $template->layouts;
            if (is_string($layouts)) {
                $layouts = json_decode($layouts, true) ?? [];
            }
            
            $layout = $layouts[$variables['type']] ?? $layouts['default'] ?? '';
            
            // Replace template variables with simple string replacement first
            foreach ($variables as $key => $value) {
                if (is_string($value)) {
                    $layout = str_replace("{{ $key }}", $value, $layout);
                } elseif (is_object($value) && method_exists($value, 'toArray')) {
                    foreach ($value->toArray() as $k => $v) {
                        if (is_string($v)) {
                            $layout = str_replace("{{ $key.$k }}", $v, $layout);
                        }
                    }
                }
            }
            
            // Apply plugin hooks
            $layout = $this->applyPluginHooks('before_render', $layout, $variables);
            
            // Render with Blade to process @include, @if, etc.
            try {
                $layout = Blade::render($layout, $variables);
            } catch (\Exception $e) {
                Log::error("Blade rendering failed: " . $e->getMessage());
                // Fall back to non-Blade rendering if it fails
            }
            
            return $layout;
        });
    }

    protected function applyPluginHooks(string $hookName, string $content, array $context): string
    {
        $plugins = \App\Models\Plugin::where('is_active', true)
            ->whereJsonContains('hooks', $hookName)
            ->get();

        foreach ($plugins as $plugin) {
            try {
                $content = $plugin->execute($hookName, $content, $context);
            } catch (\Exception $e) {
                Log::error("Plugin {$plugin->name} failed: " . $e->getMessage());
            }
        }

        return $content;
    }

    protected function renderFallback(array $variables): string
    {
        return view('frontend.fallback', $variables)->render();
    }
}
