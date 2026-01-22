<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GroqAiService;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class AiGeneratorController extends Controller
{
    public function generate(Request $request)
    {
        try {
            $validated = $request->validate([
                'instructions' => 'nullable|string',
                'tone' => 'required|string',
                'length' => 'required|string',
                'provider' => 'required|in:groq,openai',
                'content_type' => 'required|string',
                'target_fields' => 'required|array',
                'locale' => 'required|string',
                'existing_data' => 'nullable|array',
                'auto_translate' => 'nullable|boolean',
            ]);

            // Extract key existing data
            $existingData = $validated['existing_data'] ?? [];
            $currentLocale = $validated['locale'];
            
            // Handle translatable fields (arrays) by extracting current locale value
            $baseTitle = $existingData['name'] ?? $existingData['title'] ?? '';
            if (is_array($baseTitle)) {
                $baseTitle = $baseTitle[$currentLocale] ?? reset($baseTitle) ?? '';
            }
            
            $baseDescription = $existingData['description'] ?? $existingData['excerpt'] ?? '';
            if (is_array($baseDescription)) {
                $baseDescription = $baseDescription[$currentLocale] ?? reset($baseDescription) ?? '';
            }
            
            $results = [];
            $allLanguages = ['en', 'ro', 'de', 'fr', 'es', 'it'];

            foreach ($validated['target_fields'] as $field) {
                $prompt = $this->buildPrompt(
                    $field,
                    $validated['instructions'] ?? '',
                    $validated['tone'],
                    $validated['length'],
                    $validated['content_type'],
                    $validated['locale'],
                    $baseTitle,
                    $baseDescription,
                    $validated['existing_data'] ?? []
                );

                $content = $this->callAi(
                    $prompt,
                    $validated['provider']
                );

                $results[$field] = $this->processContent($field, $content);
            }

            $response = [
                'success' => true,
                'content' => $results,
            ];

            // Auto-translate if requested
            if (!empty($validated['auto_translate'])) {
                $translations = [];
                foreach ($allLanguages as $lang) {
                    if ($lang !== $currentLocale) {
                        $translations[$lang] = $this->translateContent(
                            $results,
                            $currentLocale,
                            $lang,
                            $validated['provider']
                        );
                    }
                }
                $response['translations'] = $translations;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function buildPrompt(
        string $field,
        string $instructions,
        string $tone,
        string $length,
        string $contentType,
        string $locale,
        string $baseTitle,
        string $baseDescription,
        array $existingData
    ): string {
        $fieldPrompts = [
            'name' => 'Generate a compelling, SEO-friendly name based on the existing title. Maximum 100 characters. Return ONLY the name.',
            'title' => 'Generate a compelling title based on the existing information. Maximum 70 characters. Return ONLY the title.',
            'description' => 'Generate detailed description using HTML (p, strong, em, ul, li). Be engaging and informative based on the title.',
            'content' => 'Generate comprehensive content using HTML (h2, h3, p, strong, em, ul, ol, li). Well-structured with clear sections based on the title.',
            'excerpt' => 'Generate compelling excerpt (150-200 characters) based on the title. Hook the reader. Return ONLY the excerpt.',
            'meta_title' => 'Generate SEO-optimized meta title based on the existing title. Maximum 60 characters. Return ONLY the title.',
            'meta_description' => 'Generate SEO-optimized meta description based on the existing information. Maximum 160 characters. Include call-to-action. Return ONLY the description.',
            'meta_keywords' => 'Generate 5-10 relevant SEO keywords based on the title, comma-separated. Return ONLY the keywords.',
            'button1_text' => 'Generate compelling call-to-action button text. Maximum 25 characters. Return ONLY the button text.',
            'button2_text' => 'Generate secondary call-to-action button text. Maximum 25 characters. Return ONLY the button text.',
        ];

        // Build context from existing data
        $context = "";
        if ($baseTitle) {
            $context .= "Main Title/Name: " . strip_tags($baseTitle) . "\n";
        }
        if ($baseDescription) {
            $value = is_string($baseDescription) ? $baseDescription : json_encode($baseDescription);
            $context .= "Description/Keywords: " . strip_tags($value) . "\n";
        }
        
        // Add category if exists
        if (isset($existingData['category_id']) || isset($existingData['category'])) {
            $context .= "Category: " . ($existingData['category'] ?? $existingData['category_id']) . "\n";
        }
        
        $context .= "Language: {$locale}\n";
        $context .= "Content Type: {$contentType}\n";

        $prompt = "You are a professional {$contentType} content writer and SEO expert.\n\n";
        $prompt .= "Task: " . ($fieldPrompts[$field] ?? 'Generate content for this field.') . "\n\n";
        
        if ($context) {
            $prompt .= "EXISTING INFORMATION (use this as basis):\n{$context}\n\n";
        }
        
        if ($instructions) {
            $prompt .= "Additional Instructions: {$instructions}\n\n";
        }
        
        $prompt .= "Tone: {$tone}\n";
        $prompt .= "Length: {$length}\n\n";
        $prompt .= "Generate the content for '{$field}' field now:";

        return $prompt;
    }

    protected function callAi(string $prompt, string $provider): string
    {
        if ($provider === 'groq') {
            return $this->callGroq($prompt);
        } else {
            return $this->callOpenAi($prompt);
        }
    }

    protected function callGroq(string $prompt): string
    {
        try {
            $client = new Client([
                'base_uri' => 'https://api.groq.com/openai/v1/',
                'headers' => [
                    'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 120,
            ]);

            $response = $client->post('chat/completions', [
                'json' => [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 8000,
                    'temperature' => 0.7,
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['choices'][0]['message']['content'] ?? '';
            
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            \Log::error('Groq API Client Error: ' . $errorBody);
            throw new \Exception('Groq API Error: ' . $errorBody);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            \Log::error('Groq API Server Error: ' . $errorBody);
            throw new \Exception('Groq API Server Error: ' . $errorBody);
        } catch (\Exception $e) {
            \Log::error('Groq API Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function callOpenAi(string $prompt): string
    {
        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'timeout' => 120,
        ]);

        $response = $client->post('chat/completions', [
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 4000,
                'temperature' => 0.7,
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        return $result['choices'][0]['message']['content'] ?? '';
    }

    protected function processContent(string $field, string $content): string
    {
        $content = trim($content);
        $content = preg_replace('/```html?\n?/', '', $content);
        $content = preg_replace('/```\n?/', '', $content);
        
        $plainTextFields = ['name', 'title', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords', 'button1_text', 'button2_text'];
        if (in_array($field, $plainTextFields)) {
            $content = strip_tags($content);
        }

        return $content;
    }

    protected function translateContent(array $content, string $fromLang, string $toLang, string $provider): array
    {
        $translated = [];
        
        $languageNames = [
            'en' => 'English',
            'ro' => 'Romanian',
            'de' => 'German',
            'fr' => 'French',
            'es' => 'Spanish',
            'it' => 'Italian',
        ];

        foreach ($content as $field => $text) {
            if (empty($text)) {
                $translated[$field] = '';
                continue;
            }

            $isHtml = in_array($field, ['description', 'content']);
            
            $prompt = "Translate the following text from {$languageNames[$fromLang]} to {$languageNames[$toLang]}.\n\n";
            
            if ($isHtml) {
                $prompt .= "The text contains HTML formatting. Preserve ALL HTML tags exactly as they are. Only translate the text content.\n\n";
            } else {
                $prompt .= "Return ONLY the translated text, nothing else.\n\n";
            }
            
            $prompt .= "Text to translate:\n{$text}\n\n";
            $prompt .= "Translated text in {$languageNames[$toLang]}:";

            $translatedText = $this->callAi($prompt, $provider);
            $translated[$field] = $this->processContent($field, $translatedText);
        }

        return $translated;
    }
}
