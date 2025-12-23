<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\AiGeneration;
use Illuminate\Support\Facades\Log;

class GroqAiService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl = 'https://api.groq.com/openai/v1/';
    protected string $model = 'llama-3.3-70b-versatile'; // or mixtral-8x7b-32768

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY', '');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 120,
        ]);
    }

    /**
     * Generate a complete template/theme using Groq AI
     */
    public function generateTemplate(string $description, array $options = []): array
    {
        $startTime = microtime(true);
        
        $prompt = $this->buildTemplatePrompt($description, $options);
        
        $generation = AiGeneration::create([
            'type' => 'template',
            'prompt' => $prompt,
            'parameters' => $options,
            'model' => $this->model,
            'user_id' => auth()->id() ?? 1,
            'status' => 'pending',
        ]);

        try {
            $response = $this->makeRequest($prompt);
            
            $generationTime = (int)((microtime(true) - $startTime) * 1000);
            
            $generation->update([
                'response' => $response['content'],
                'status' => 'completed',
                'tokens_used' => $response['tokens_used'] ?? null,
                'generation_time' => $generationTime,
            ]);

            return [
                'success' => true,
                'data' => $this->parseTemplateResponse($response['content']),
                'generation_id' => $generation->id,
            ];

        } catch (\Exception $e) {
            $generation->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Groq AI Template Generation Failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate a plugin using Groq AI
     */
    public function generatePlugin(string $description, array $requirements = []): array
    {
        $startTime = microtime(true);
        
        $prompt = $this->buildPluginPrompt($description, $requirements);
        
        $generation = AiGeneration::create([
            'type' => 'plugin',
            'prompt' => $prompt,
            'parameters' => $requirements,
            'model' => $this->model,
            'user_id' => auth()->id() ?? 1,
            'status' => 'pending',
        ]);

        try {
            $response = $this->makeRequest($prompt);
            
            $generationTime = (int)((microtime(true) - $startTime) * 1000);
            
            $generation->update([
                'response' => $response['content'],
                'status' => 'completed',
                'tokens_used' => $response['tokens_used'] ?? null,
                'generation_time' => $generationTime,
            ]);

            return [
                'success' => true,
                'data' => $this->parsePluginResponse($response['content']),
                'generation_id' => $generation->id,
            ];

        } catch (\Exception $e) {
            $generation->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Groq AI Plugin Generation Failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate template blocks (hero, footer, etc.)
     */
    public function generateBlock(string $blockType, array $options = []): array
    {
        $prompt = $this->buildBlockPrompt($blockType, $options);
        
        try {
            $response = $this->makeRequest($prompt);
            
            return [
                'success' => true,
                'data' => $this->parseBlockResponse($response['content']),
            ];

        } catch (\Exception $e) {
            Log::error('Groq AI Block Generation Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Improve existing code/content
     */
    public function improveCode(string $code, string $instructions): array
    {
        $prompt = "Improve the following code based on these instructions: {$instructions}\n\nCode:\n```\n{$code}\n```\n\nProvide only the improved code, properly formatted.";
        
        try {
            $response = $this->makeRequest($prompt);
            
            return [
                'success' => true,
                'improved_code' => $this->extractCode($response['content']),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Make request to Groq API
     */
    /**
     * Generate content using Groq AI (for blog posts, pages, etc)
     */
    public function generateContent(string $prompt, int $maxTokens = 4000): array
    {
        $startTime = microtime(true);
        
        $generation = AiGeneration::create([
            'type' => 'content',
            'prompt' => $prompt,
            'model' => $this->model,
            'user_id' => auth()->id() ?? 1,
            'status' => 'pending',
        ]);

        try {
            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a professional content writer. Create engaging, SEO-friendly content in HTML format.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => $maxTokens,
                    'top_p' => 1,
                    'stream' => false,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $content = $body['choices'][0]['message']['content'] ?? '';
            $tokensUsed = $body['usage']['total_tokens'] ?? null;
            
            $generationTime = (int)((microtime(true) - $startTime) * 1000);
            
            $generation->update([
                'response' => $content,
                'status' => 'completed',
                'tokens_used' => $tokensUsed,
                'generation_time' => $generationTime,
            ]);

            return [
                'success' => true,
                'content' => $content,
                'tokens_used' => $tokensUsed,
                'generation_id' => $generation->id,
            ];

        } catch (\Exception $e) {
            $generation->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function makeRequest(string $prompt, int $maxTokens = 4000): array
    {
        try {
            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert web developer and designer specialized in creating beautiful, modern, responsive websites using HTML, CSS, JavaScript, and PHP. You provide clean, production-ready code with best practices.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => $maxTokens,
                    'top_p' => 1,
                    'stream' => false,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            return [
                'content' => $body['choices'][0]['message']['content'] ?? '',
                'tokens_used' => $body['usage']['total_tokens'] ?? null,
            ];

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $error = json_decode($e->getResponse()->getBody()->getContents(), true);
                throw new \Exception($error['error']['message'] ?? 'Groq API request failed');
            }
            throw $e;
        }
    }

    /**
     * Build template generation prompt
     */
    protected function buildTemplatePrompt(string $description, array $options): string
    {
        $style = $options['style'] ?? 'modern';
        $colorScheme = $options['color_scheme'] ?? 'blue';
        $features = $options['features'] ?? [];

        return "Create a complete, production-ready website template with the following requirements:

Description: {$description}
Style: {$style}
Color Scheme: {$colorScheme}
Features: " . implode(', ', $features) . "

Generate a complete template structure with:
1. HTML structure (using Blade syntax for Laravel)
2. Modern CSS (Tailwind CSS preferred, or custom CSS)
3. JavaScript for interactivity
4. Responsive design (mobile-first)
5. Clean, semantic code
6. SEO-optimized structure

Provide the response in this JSON format:
{
    \"name\": \"Template Name\",
    \"description\": \"Template description\",
    \"html\": \"<full HTML/Blade code>\",
    \"css\": \"/* Complete CSS */\",
    \"js\": \"// Complete JavaScript\",
    \"layouts\": {
        \"header\": \"<header HTML>\",
        \"footer\": \"<footer HTML>\",
        \"sidebar\": \"<sidebar HTML>\"
    },
    \"color_scheme\": {
        \"primary\": \"#hexcode\",
        \"secondary\": \"#hexcode\",
        \"accent\": \"#hexcode\"
    }
}";
    }

    /**
     * Build plugin generation prompt
     */
    protected function buildPluginPrompt(string $description, array $requirements): string
    {
        $hooks = $requirements['hooks'] ?? [];
        $functionality = $requirements['functionality'] ?? '';

        return "Create a complete, production-ready WordPress-style plugin for Laravel CMS with:

Description: {$description}
Functionality: {$functionality}
Required Hooks: " . implode(', ', $hooks) . "

Generate PHP code that:
1. Is secure and follows Laravel best practices
2. Uses proper Laravel conventions
3. Includes necessary hooks and filters
4. Has clear documentation
5. Is modular and maintainable

Provide the response in this JSON format:
{
    \"name\": \"Plugin Name\",
    \"slug\": \"plugin-slug\",
    \"description\": \"Description\",
    \"code\": \"<?php // Complete plugin code ?>\",
    \"hooks\": {
        \"hook_name\": \"function_name\"
    },
    \"config\": {
        \"settings\": []
    }
}";
    }

    /**
     * Build block generation prompt
     */
    protected function buildBlockPrompt(string $blockType, array $options): string
    {
        return "Create a modern, responsive {$blockType} section for a website with:

Options: " . json_encode($options) . "

Provide HTML, CSS, and JavaScript if needed. Make it beautiful, accessible, and production-ready.

Return in JSON format:
{
    \"html\": \"<complete HTML>\",
    \"css\": \"/* complete CSS */\",
    \"js\": \"// JavaScript if needed\"
}";
    }

    /**
     * Parse template response from AI
     */
    protected function parseTemplateResponse(string $response): array
    {
        // Try to extract JSON
        if (preg_match('/\{[\s\S]*\}/', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        // Fallback: parse manually
        return [
            'html' => $this->extractCodeBlock($response, 'html'),
            'css' => $this->extractCodeBlock($response, 'css'),
            'js' => $this->extractCodeBlock($response, 'js'),
        ];
    }

    /**
     * Parse plugin response from AI
     */
    protected function parsePluginResponse(string $response): array
    {
        if (preg_match('/\{[\s\S]*\}/', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        return [
            'code' => $this->extractCode($response),
        ];
    }

    /**
     * Parse block response from AI
     */
    protected function parseBlockResponse(string $response): array
    {
        if (preg_match('/\{[\s\S]*\}/', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        return [
            'html' => $this->extractCodeBlock($response, 'html'),
            'css' => $this->extractCodeBlock($response, 'css'),
            'js' => $this->extractCodeBlock($response, 'js'),
        ];
    }

    /**
     * Extract code from markdown code blocks
     */
    protected function extractCode(string $content): string
    {
        if (preg_match('/```(?:php|html|javascript|css)?\s*([\s\S]*?)```/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return $content;
    }

    /**
     * Extract specific code block type
     */
    protected function extractCodeBlock(string $content, string $type): string
    {
        if (preg_match("/```{$type}\s*([\s\S]*?)```/i", $content, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }
}
