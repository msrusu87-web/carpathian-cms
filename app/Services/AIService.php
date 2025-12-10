<?php

namespace App\Services;

use App\Models\AiSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected ?AiSetting $provider = null;

    public function __construct(?string $providerName = null)
    {
        if ($providerName) {
            $this->provider = AiSetting::where('provider', $providerName)
                ->where('is_active', true)
                ->first();
        } else {
            $this->provider = AiSetting::getDefault();
        }
    }

    public function chat(string $prompt, array $context = []): ?string
    {
        if (!$this->provider || !$this->provider->api_key) {
            throw new \Exception('No AI provider configured or API key missing');
        }

        switch ($this->provider->provider) {
            case 'groq':
                return $this->chatGroq($prompt, $context);
            case 'chatgpt':
                return $this->chatOpenAI($prompt, $context);
            case 'gemini':
                return $this->chatGemini($prompt, $context);
            default:
                throw new \Exception('Unsupported AI provider: ' . $this->provider->provider);
        }
    }

    protected function chatGroq(string $prompt, array $context = []): ?string
    {
        try {
            $model = $this->provider->model ?? 'llama-3.3-70b-versatile';
            
            $messages = [];
            if (!empty($context['system'])) {
                $messages[] = [
                    'role' => 'system',
                    'content' => $context['system']
                ];
            }
            
            $messages[] = [
                'role' => 'user',
                'content' => $prompt
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->provider->api_key,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $context['temperature'] ?? 0.7,
                'max_tokens' => $context['max_tokens'] ?? 2000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('Groq API error', ['response' => $response->body()]);
            throw new \Exception('Groq API error: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Groq chat error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function chatOpenAI(string $prompt, array $context = []): ?string
    {
        try {
            $model = $this->provider->model ?? 'gpt-4';
            
            $messages = [];
            if (!empty($context['system'])) {
                $messages[] = [
                    'role' => 'system',
                    'content' => $context['system']
                ];
            }
            
            $messages[] = [
                'role' => 'user',
                'content' => $prompt
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->provider->api_key,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $context['temperature'] ?? 0.7,
                'max_tokens' => $context['max_tokens'] ?? 2000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('OpenAI API error', ['response' => $response->body()]);
            throw new \Exception('OpenAI API error: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('OpenAI chat error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function chatGemini(string $prompt, array $context = []): ?string
    {
        try {
            $model = $this->provider->model ?? 'gemini-pro';
            
            $response = Http::timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ],
                [
                    'headers' => [
                        'x-goog-api-key' => $this->provider->api_key,
                        'Content-Type' => 'application/json',
                    ]
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            Log::error('Gemini API error', ['response' => $response->body()]);
            throw new \Exception('Gemini API error: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Gemini chat error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function testConnection(): bool
    {
        try {
            $response = $this->chat('Hello, respond with just "OK" if you receive this.', [
                'max_tokens' => 10
            ]);
            return !empty($response);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getProvider(): ?AiSetting
    {
        return $this->provider;
    }
}
