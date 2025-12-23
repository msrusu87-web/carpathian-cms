<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageGenerationService
{
    protected string $apiKey;
    protected string $model = 'dall-e-3';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key', env('OPENAI_API_KEY', ''));
    }

    /**
     * Generate image from text prompt using DALL-E
     */
    public function generate(string $prompt, array $options = []): ?array
    {
        if (empty($this->apiKey)) {
            throw new \Exception('OpenAI API key not configured');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.openai.com/v1/images/generations', [
                'model' => $options['model'] ?? $this->model,
                'prompt' => $prompt,
                'n' => $options['n'] ?? 1,
                'size' => $options['size'] ?? '1024x1024',
                'quality' => $options['quality'] ?? 'standard',
                'response_format' => 'url',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? null;
            }

            Log::error('DALL-E API error', ['response' => $response->body()]);
            throw new \Exception('Image generation failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Image generation error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Generate and save image to storage
     */
    public function generateAndSave(string $prompt, array $options = []): ?string
    {
        $images = $this->generate($prompt, $options);
        
        if (empty($images)) {
            return null;
        }

        $imageUrl = $images[0]['url'];
        $imageContent = file_get_contents($imageUrl);
        
        if ($imageContent === false) {
            throw new \Exception('Failed to download generated image');
        }

        $filename = 'ai-generated/' . Str::slug(Str::limit($prompt, 50)) . '-' . time() . '.png';
        Storage::disk('public')->put($filename, $imageContent);

        return Storage::disk('public')->url($filename);
    }

    /**
     * Generate image variations
     */
    public function createVariation(string $imagePath, array $options = []): ?array
    {
        if (empty($this->apiKey)) {
            throw new \Exception('OpenAI API key not configured');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(120)->attach(
                'image', file_get_contents($imagePath), 'image.png'
            )->post('https://api.openai.com/v1/images/variations', [
                'n' => $options['n'] ?? 1,
                'size' => $options['size'] ?? '1024x1024',
                'response_format' => 'url',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? null;
            }

            Log::error('DALL-E variation error', ['response' => $response->body()]);
            throw new \Exception('Image variation failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Image variation error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Improve image prompt using AI
     */
    public function improvePrompt(string $prompt): string
    {
        $aiService = new AIService();
        
        $systemPrompt = "You are an expert at writing prompts for DALL-E image generation. Transform the user's simple prompt into a detailed, vivid description that will produce better images. Keep it under 400 characters.";
        
        $improved = $aiService->chat($prompt, [
            'system' => $systemPrompt,
            'max_tokens' => 300,
        ]);

        return $improved ?? $prompt;
    }
}
