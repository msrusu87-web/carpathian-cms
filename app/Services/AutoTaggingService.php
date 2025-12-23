<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

class AutoTaggingService
{
    protected AIService $aiService;

    public function __construct()
    {
        $this->aiService = new AIService();
    }

    /**
     * Generate tags for content using AI
     */
    public function generateTags(string $content, int $maxTags = 10): array
    {
        $systemPrompt = "You are a content tagging expert. Analyze the content and generate relevant, SEO-friendly tags. Return ONLY a comma-separated list of tags, nothing else. Max {$maxTags} tags.";
        
        $prompt = "Generate tags for this content:\n\n" . Str::limit($content, 3000);
        
        try {
            $response = $this->aiService->chat($prompt, [
                'system' => $systemPrompt,
                'max_tokens' => 200,
                'temperature' => 0.5,
            ]);

            if (!$response) {
                return [];
            }

            // Parse comma-separated tags
            $tags = array_map('trim', explode(',', $response));
            $tags = array_filter($tags);
            $tags = array_slice($tags, 0, $maxTags);

            // Clean and normalize tags
            return array_map(function($tag) {
                return Str::lower(Str::limit($tag, 50, ''));
            }, $tags);

        } catch (\Exception $e) {
            \Log::error('Auto-tagging error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Generate and attach tags to a model
     */
    public function autoTag($model, int $maxTags = 10): array
    {
        $content = $this->extractContent($model);
        $tagNames = $this->generateTags($content, $maxTags);
        
        $tags = [];
        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );
            $tags[] = $tag->id;
        }

        if (method_exists($model, 'tags')) {
            $model->tags()->sync($tags);
        }

        return $tagNames;
    }

    /**
     * Extract relevant content from model
     */
    protected function extractContent($model): string
    {
        $content = '';

        if (isset($model->title)) {
            $content .= $model->title . '. ';
        }

        if (isset($model->excerpt)) {
            $content .= $model->excerpt . ' ';
        }

        if (isset($model->content)) {
            // Strip HTML tags
            $content .= strip_tags($model->content);
        } elseif (isset($model->body)) {
            $content .= strip_tags($model->body);
        }

        return $content;
    }

    /**
     * Suggest additional tags based on existing ones
     */
    public function suggestRelatedTags(array $existingTags, int $count = 5): array
    {
        $systemPrompt = "You are a content tagging expert. Given existing tags, suggest {$count} related tags that would complement them. Return ONLY a comma-separated list, nothing else.";
        
        $prompt = "Existing tags: " . implode(', ', $existingTags) . "\n\nSuggest {$count} related tags:";
        
        try {
            $response = $this->aiService->chat($prompt, [
                'system' => $systemPrompt,
                'max_tokens' => 100,
                'temperature' => 0.7,
            ]);

            if (!$response) {
                return [];
            }

            $tags = array_map('trim', explode(',', $response));
            $tags = array_filter($tags);
            
            return array_map(function($tag) {
                return Str::lower(Str::limit($tag, 50, ''));
            }, array_slice($tags, 0, $count));

        } catch (\Exception $e) {
            \Log::error('Related tags suggestion error', ['error' => $e->getMessage()]);
            return [];
        }
    }
}
